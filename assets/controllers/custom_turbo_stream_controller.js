import { Controller } from '@hotwired/stimulus';
import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";

export default class extends Controller {
    static values = {
        token: String,
        hub: String,
        topic: String
    };

    connect() {
        this.eventSource = new EventSource(this.hubValue + '?topic=' + encodeURIComponent(this.topicValue), {
            withCredentials: false,
            headers: {
                'Authorization': 'Bearer ' + this.tokenValue,
            }
        });
        connectStreamSource(this.eventSource);
    }

    // Close the subscription when the element leaves the DOM (e.g. a Live Component re-render),
    // otherwise every re-render would leave an extra open connection receiving duplicated streams.
    disconnect() {
        if (this.eventSource) {
            disconnectStreamSource(this.eventSource);
            this.eventSource.close();
            this.eventSource = null;
        }
    }
}
