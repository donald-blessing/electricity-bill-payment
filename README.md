# Electricity Bill Payment System

This is a Laravel-based electricity bill payment system that implements an event-driven architecture for handling bill
verification, payments, and notifications.

## Features

- Bill verification and creation
- Wallet-based payment processing
- Event-driven architecture using Laravel's event system
- SMS notifications for important events
- Support for multiple electricity providers
- Mock implementations for third-party services

## Setup Instructions

1. Clone the repository
2. Install dependencies:

```bash
composer install
```

3. Set up your environment variables:

```bash
cp .env.example .env
```

4. Run migrations:

```bash
php artisan migrate
```

5. Start the Laravel queue worker:

```bash
php artisan queue:work
```

## API Endpoints

### POST /api/electricity/verify

Create a new electricity bill

**Request Body:**

```json
{
    "amount": 100.00,
    "provider": "irecharge"
}
```

### POST /api/vend/{validationRef}/pay

Process payment for a specific bill

### POST /api/wallets/{id}/add-funds

Add funds to a user's wallet

**Request Body:**

```json
{
    "amount": 500.00
}
```

## Event System

The system implements the following events:

- `BillCreated`: Triggered when a new bill is verified
- `PaymentCompleted`: Triggered when a payment is successful
- `LowBalanceDetected`: Triggered when wallet balance falls below threshold

## SMS Notifications

The system sends SMS notifications for:

1. New bill creation
2. Successful payments (includes token)
3. Low wallet balance alerts

The SMS service is implemented as a mock service but can be replaced with actual providers like Twilio or Nexmo by
implementing the appropriate gateway.

## Design Decisions

1. **Event-Driven Architecture**
    - Used Laravel's built-in event system for loose coupling
    - Events trigger notifications and updates asynchronously

2. **Action and Service Layer Pattern**
    - Separated business logic into service classes
    - Allows for easy testing and maintenance

3. **Provider Factory Pattern**
    - Implemented factory pattern for electricity providers
    - Easily extendable for new providers

## Configuration

### SMS Configuration

Set the following in your .env file:

```
SMS_PROVIDER=mock
SMS_LOW_BALANCE_THRESHOLD=1000
```

### Queue Configuration

```
QUEUE_CONNECTION=database
```

## Testing

Run the test suite:

```bash
php artisan test
```
