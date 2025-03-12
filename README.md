🚀 Project Setup Guide
1️⃣ Install Dependencies

composer install
npm install

2️⃣ Setup User Roles
Run the following command to seed the database with roles:

php artisan db:seed --class=RoleSeeder

3️⃣ Create an Admin User
To create an admin user, run:

php artisan db:seed --class=AdminUserSeeder

📌 Note: The admin credentials will be displayed in the terminal.

4️⃣ Start Webhook Listeners

✅ Listen for Payment Status Updates (Paid)

export STRIPE_API_KEY="sk_test_51Q6UEM2Lzh4cc0WOHGSJkJ326Si2Y400Y6I0zJslXAfYqtufzTgr0ReLRT0Z2cLQIuwv8iyJPyLNYiDOExgT5cHf00uhJRmw4E"
stripe listen --forward-to http://127.0.0.1:8000/api/stripe/webhook --api-key $STRIPE_API_KEY

✅ Listen for Expired Payment Links

export STRIPE_API_KEY="sk_test_51Q6UEM2Lzh4cc0WOHGSJkJ326Si2Y400Y6I0zJslXAfYqtufzTgr0ReLRT0Z2cLQIuwv8iyJPyLNYiDOExgT5cHf00uhJRmw4E"
stripe listen --forward-to http://127.0.0.1:8000/api/stripe/webhookExpiration --api-key $STRIPE_API_KEY

5️⃣ Run Laravel Queue Worker

php artisan queue:work

6️⃣ SMTP Configuration

Important: Use Google App Passwords to generate an app password for SMTP authentication.
