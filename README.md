# Real-Time Product Display with Free API

## Set up and run the application

Clone the project

```bash
git clone https://github.com/jamiul/laravel-pusher.git
cd laravel-pusher
```

Start the server

```bash
docker compose -p pusher up -d
```

Go to php bash

```bash
docker exec -it --user root pusher-app bash
```

Install dependencies

```bash
composer install
npm install && npm run dev
```

Stop the server

```bash
docker compose -p pusher down -v
```

## How Pusher Integration Works with Laravel for Real-Time Updates
Pusher is integrated with Laravel to enable real-time updates through a WebSocket connection.

### Connection Flow
- Server-Side Setup:
The .env configuration (BROADCAST_DRIVER=pusher). This tells to use Pusher when broadcasting events.
- Client-Side Connection: In your JavaScript, Laravel Echo creates a WebSocket connection to Pusher's servers using your app credentials. This establishes a persistent connection between your user's browser and Pusher.
- Channel Subscription: Your JavaScript subscribes to specific channels (in this case, the 'products' channel). This tells Pusher which events your client is interested in receiving.

### Event Broadcasting Process
When a new product is added:
- Event Triggering: ProductController calls event(new NewProductAdded($product)) which broadcasts the event with the product data.
- Laravel Broadcasting: Laravel identifies that this event implements ShouldBroadcast and sends it to your configured broadcast driver (Pusher).
- Pusher Distribution: Pusher receives this event and distributes it to all connected clients who are subscribed to the 'products' channel
- Client-Side Handling: In the browser, your JavaScript event listener (channel.bind('App\\Events\\NewProductAdded', function(data) {...})) is triggered when the event is received.
- DOM Update: Your callback function runs, creating a new product card and inserting it into the page without requiring a refresh.

### The Technical Components

- Laravel Event: A PHP class that defines what data gets sent and on which channel
- Pusher: The service that manages the WebSocket connections and message distribution
- Laravel Echo: A JavaScript library that simplifies connecting to broadcasting services
- JavaScript Event Listeners: Code that responds when new messages arrive