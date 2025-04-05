### Laravel Interview Task: Real-Time Product Display with Free API

### Task Objective:
Create a small product display application using Laravel that retrieves product data from a free product API and displays it in real-time using Pusher. The app should automatically update the product list when new products are added.

### Requirements:

1. API Integration:
   - Use a free public product API to fetch product data (e.g., [Fake Store API](https://fakestoreapi.com/)).
   - Display the product name, description, and price.

2. Real-Time Updates:
   - Use Pusher for real-time updates when new products are added to the database.
   - Ensure that when a new product is fetched or added to the database, all users viewing the page will automatically see the update without refreshing the page.

3. Basic Product Display:
   - Display a simple list of products with their name, description, and price.

4. Laravel Event Broadcasting:
   - Set up event broadcasting in Laravel using Pusher to notify all connected clients when a new product is added or updated.

5. Frontend:
   - Create a simple Blade view to display the products.
   - Use JavaScript (with Pusher) to listen for real-time events and dynamically update the product list.

### Steps to Complete the Task:

1. Set up a New Laravel Project:
   - Create a new Laravel application.

2. Install Necessary Packages:
   - Install Pusher for real-time updates.
   - Use Guzzle to make HTTP requests to the product API.

3. Set up Pusher:
   - Create a Pusher account and set up a new app.
   - Add Pusher credentials in the `.env` file and configure broadcasting.

4. Create a Product Model & Migration:
   - Create a `Product` model with a simple migration (e.g., name, description, price).

5. Create a Controller:
   - In the controller, fetch products from the external API (e.g., `fakestoreapi.com`) and store them in the database.
   - Broadcast an event to notify clients when a product is fetched or added.

6. Create Events & Listeners:
   - Set up a ProductUpdated event to trigger real-time updates using Pusher.

7. Create a Blade View:
   - Use Blade to create a page that displays the list of products.
   - Use JavaScript to listen for real-time product updates.

8. Testing:
   - Ensure the real-time updates work as expected when a product is added or updated.