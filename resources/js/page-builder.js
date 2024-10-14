// This is a placeholder for the Page Builder JavaScript

// globals.Marionette = require('backbone.marionette');
globals.Marionette = require('elementor/assets/lib/backbone/backbone.marionette');

console.log('attempting module import')
import '../../elementor/assets/dev/js/modules/modules';

console.log("Attempting editor import")
import "../../elementor/assets/dev/js/editor/editor";

console.log('Page Builder loaded', window.elementor);
