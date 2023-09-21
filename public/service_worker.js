const cache_name = 'journey_recorder';

self.addEventListener(
    'fetch'
    , e => {
        console.log(`Received request ${e.request.method} ${e.request.url}`);
        e.respondWith(
            (async () => {
                try {
                    const response = await fetch(e.request);
                    if (!['opaque', 'opaqueredirect'].includes(response.type) && !response.ok) {
                        console.log(response);
                        throw new Error(`HTTP error while fetching ${e.request.url}`);
                    }
                    console.log(`${e.request.method} ${e.request.url} from network successful.`);
                    if (['http:', 'https:'].includes(new URL(e.request.url).protocol)) {
                        console.log(`Caching ${e.request.url}`);
                        const cached_response = response.clone();
                        caches.open(cache_name).then(cache => cache.put(e.request, cached_response));
                    }
                    return response;
                } catch (ex) {
                    console.log(`${e.request.method} ${e.request.url} from network failed.`);
                    const result = await caches.match(e.request);
                    if (result) {
                        console.log(`Returning cached response for ${e.request.url}`);
                        return result;
                    }
                    throw ex;
                }
            })()
        );
    }
);