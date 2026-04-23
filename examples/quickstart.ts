import {OrderClient} from '../src/index.js';

const BASE = process.env.API_BASE || 'http://localhost:8080';

(async () => {
    const c = new OrderClient({baseUrl: BASE});
    const o = await c.createOrder({totalAmount: 1999, currency: 'USD', customerId: 'cus_sdk'});
    console.log('order', o);
    await c.transition(o.id, 'confirm', 'sdk-qstart-1');
    console.log('confirmed');
})().catch(e => {
    console.error(e);
    process.exit(1);
});
