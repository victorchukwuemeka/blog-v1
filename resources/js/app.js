import.meta.glob([
    '../img/**',
    '../svg/**',
])

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm'
import Autosize from '@marcreichel/alpine-autosize'

Alpine.plugin(Autosize)

Livewire.start()
