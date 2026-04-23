import {OrderClient, idempotencyKey} from '../src/index.js';

async function run() {
    const client = new OrderClient({
        base: process.env.BASE ?? 'https://api.smartresponsor.local',
        token: process.env.TOKEN
    });
    const o = await client.createOrder({totalAmount: 1999, currency: 'USD', customerId: 'cus_001'});
    await client.transition(o.id, 'confirm', idempotencyKey());
    console.log(JSON.stringify({orderId: o.id, status: 'confirmed'}));
}

run().catch(e => {
    console.error(e);
    process.exit(2);
});
