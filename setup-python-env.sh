#!/bin/bash

# Exit on error.
set -e

echo "Setting up Python virtual environment for Python dependencies..."

# Check if Python 3.8+ is installed.
if ! command -v python3 &> /dev/null; then
    echo "Python 3 is not installed. Please install Python 3.8 or higher."
    exit 1
fi

# Create virtual environment if it doesn't exist.
if [ ! -d "venv" ]; then
    echo "Creating virtual environment..."
    python3 -m venv venv
else
    echo "Virtual environment already exists"
fi

# Activate virtual environment.
echo "Activating virtual environment..."
source venv/bin/activate

# Upgrade pip.
echo "Upgrading pip..."
pip install --upgrade pip

# Install required packages.
echo "Installing Camoufox with GeoIP support and youtube-transcript-api..."
pip install camoufox[geoip] youtube-transcript-api

# Verify installation.
echo "Verifying installation..."
if python -c "from camoufox.sync_api import Camoufox; import youtube_transcript_api; print('Dependencies successfully installed!')" ; then
    echo "Setup completed successfully!"
else
    echo "Installation verification failed"
    exit 1
fi

# Deactivate virtual environment.
deactivate

echo "\nAll done! Your Python environment is ready.\nTo activate the environment manually, run:\nsource venv/bin/activate\n\n(You ran this script as setup-python-env.sh)\n"
