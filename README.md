# 🚀 Laravel Slack Observer Demo

This is a minimal yet professional Laravel 12 project that demonstrates how to send **Slack notifications** via **queued jobs** whenever a new user is registered.

It is designed for:
- Clean code architecture
- Queue-friendly design
- Slack integration using Webhooks
- Modern testing using **Pest PHP**
- SQLite as the local database

---

## 🛠 Features

✅ Slack webhook integration  
✅ Job-based architecture (`SendSlackNotification` implements `ShouldQueue`)  
✅ Environment-based config for security  
✅ Full test coverage using **Pest**  
✅ Laravel Queue-ready  
✅ Clean code and error logging with `Http::fake`, `Log::warning`, `try/catch`  
✅ Portable SQLite DB (no need to set up MySQL)

---

## 📸 Screenshot

![Slack Notification](https://tinyurl.com/ylmae76x)

---

## 📂 Project Structure

```
app/
├── Jobs/
│   └── SendSlackNotification.php     # Core job logic
config/
├── slack.php                         # Slack webhook config
database/
├── database.sqlite                   # Local DB
routes/
├── web.php                           # Test route to trigger notification
tests/
├── Feature/
│   └── SendSlackNotificationTest.php # Pest-powered test cases
```

---

## ⚙️ Setup Instructions

### 1. Clone this Repo

```bash
git clone https://github.com/your-username/laravel-slack-observer-demo.git
cd laravel-slack-observer-demo
```

### 2. Install Dependencies

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

### 3. Configure Slack Webhook

Edit your `.env`:

```env
SLACK_NEW_REGISTRATION_WEBHOOK=https://hooks.slack.com/services/...
```

Add to `config/slack.php`:

```php
return [
    'webhook_urls' => [
        'new_registration' => env('SLACK_NEW_REGISTRATION_WEBHOOK'),
    ],
];
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Start Local Server

```bash
php artisan serve
```

Visit:  
📍 `http://localhost:8000/test-user`  
✅ If everything works, you'll see a Slack notification posted.

---

## 🧪 Running Tests (Pest)

```bash
php artisan test
```

Output:

```bash
PASS  Tests\Feature\SendSlackNotificationTest
✓ sends Slack message successfully
✓ logs error if Slack webhook fails
✓ logs exception if Slack call throws
```

---

## 👨‍💻 Built With

- Laravel 10+
- PHP 8.2+
- Pest PHP for testing
- SQLite for local dev
- Slack Incoming Webhooks
- Laravel Queues (sync driver)

---

## 🙋‍♂️ About the Author

**👋 Mohammad Umair**  
Senior Laravel Developer | 15+ Years of Experience  
Remote from 🇮🇳 | Seeking full-time remote roles  
🔗 [LinkedIn](https://www.linkedin.com/in/umr4ever/) | 🔗 [GitHub](https://github.com/umr4ever)

---

## 🪄 Future Ideas

- Add support for Discord / Teams notifications  
- Add notification throttling / retry  
- Publish as a Laravel package

---

## ⭐️ Star This Repo

If you found this useful, consider giving it a ⭐ on GitHub — it helps boost visibility for more developers.

## 📝 License

This project is open-sourced under the [MIT license](LICENSE).
