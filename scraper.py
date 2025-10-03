import argparse
import json
import sys
from camoufox.sync_api import Camoufox
from urllib.parse import urlparse

def visit_with_fallback(browser, target_url):
    parsed_url = urlparse(target_url)

    homepage = f"{parsed_url.scheme}://{parsed_url.netloc}"

    page = browser.new_page()

    try:
        response = page.goto(target_url, wait_until="domcontentloaded")

        if response.headers.get("x-amzn-waf-action") == "challenge" or response.status == 403:
            page.goto(homepage, wait_until="networkidle")
            page.goto(target_url, wait_until="networkidle")

        image_url = page.evaluate("""() => {
            const ogImage = document.querySelector('meta[property="og:image"]')
            if (ogImage) {
                return ogImage.getAttribute('content')
            }

            const twitterImage = document.querySelector('meta[name="twitter:image"]')
            if (twitterImage) {
                return twitterImage.getAttribute('content')
            }

            return null
        }""")

        title = page.evaluate("""() => {
            return document.title
        }""")

        content = page.evaluate("""() => {
            // Remove cookie banners.
            document.querySelectorAll('[id*="cookie"], [class*="cookie"], [id*="cky"], [class*="cky"], #sp-cc')
                .forEach(element => element.remove())

            // Remove other non-content elements.
            document.querySelectorAll('hr, iframe, link, meta, noscript, script, style, svg')
                .forEach(item => item.remove())

            document.querySelectorAll('*')
                .forEach(element => {
                    // Remove hidden elements.
                    if (window.getComputedStyle(element).display === 'none') {
                        element.remove()

                        return
                    }

                    // Remove attributes that are not needed.
                    Array.from(element.attributes).forEach(attr => {
                        if ((attr.name === 'href' || attr.name === 'src') && attr.value.startsWith('http')) {
                            return
                        }

                        if (attr.name.startsWith('aria-')) {
                            return
                        }

                        element.removeAttribute(attr.name)
                    })

                    // Remove comments.
                    element.querySelectorAll('*').forEach(comment => {
                        if (comment.nodeType === 8) {
                            comment.remove()
                        }
                    })
                })

            // Clean up the HTML even more.
            return document.body.innerHTML
                // Remove all newlines, carriage returns and tabs.
                .replace(/\\n|\\r|\\t/g, '')
                // Replace multiple spaces with a single space.
                .replace(/ +/g, ' ')
                // Remove whitespace between HTML tags.
                .replace(/>\\s+</g, '><')
        }""")

        return {
            "url": target_url,
            "imageUrl": image_url,
            "title": title,
            "content": content,
        }
    except Exception as e:
        print(f"Error visiting {target_url}: {str(e)}")
        print(f"Browser info: {browser.browser_type.name()}")
        print(f"Context options: {browser.context.options}")
        sys.exit(1)

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Visit a URL with fallback using Camoufox.")
    parser.add_argument("url", help="The target URL to visit.")
    parser.add_argument("--proxy", required=False, help="The proxy server address.")
    parser.add_argument("--proxy-username", required=False, help="The proxy username.")
    parser.add_argument("--proxy-password", required=False, help="The proxy password.")

    args = parser.parse_args()

    # Build Camoufox options and only include proxy settings when valid
    camoufox_options = {
        "geoip": True,
        "headless": True,
        "humanize": True,
    }

    if args.proxy:
        proxy_options = {"server": args.proxy}
        if args.proxy_username:
            proxy_options["username"] = args.proxy_username
        if args.proxy_password:
            proxy_options["password"] = args.proxy_password
        camoufox_options["proxy"] = proxy_options

    with Camoufox(**camoufox_options) as browser:
        result = visit_with_fallback(browser, args.url)
        print(json.dumps(result))
