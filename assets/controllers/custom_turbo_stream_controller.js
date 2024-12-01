import { Controller } from '@hotwired/stimulus';
import { connectStreamSource } from "@hotwired/turbo";

export default class extends Controller {
    static values = {
        token: String,
        hub: String,
        topic: String
    };

    async connect() {
        const eventSource = new EventSource(this.hubValue + '?topic=' + this.topicValue, {
            withCredentials: false,
            headers: {
                'Authorization': 'Bearer ' + this.tokenValue,
            }
        });
        connectStreamSource(eventSource)
    }
}