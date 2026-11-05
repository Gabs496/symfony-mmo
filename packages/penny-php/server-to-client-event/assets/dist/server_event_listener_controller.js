import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        channelUrl: String,
    }
    async connect() {
        this.eventSource = new EventSource(this.channelUrlValue);
        this.eventSource.onmessage = (event) => {
            const eventData = JSON.parse(event.data);
            document.dispatchEvent(new CustomEvent('server.' + eventData.clientEventName, eventData));
        }
    }
}
