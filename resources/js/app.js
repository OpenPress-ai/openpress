import './bootstrap';

console.log("importing marionette")
import '../../src/marionette-global'
console.log('imported marionette')

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
