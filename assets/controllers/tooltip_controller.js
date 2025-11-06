import { Controller } from '@hotwired/stimulus';
import * as bootstrap from 'bootstrap';

export default class extends Controller {
    async connect() {
        new bootstrap.Tooltip(this.element, {html: true});
    }
}