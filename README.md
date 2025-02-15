# Cache Personal Access Tokens

## Overview

To optimize API requests, cache Personal Access Tokens in the database instead of querying them with every request. This reduces unnecessary queries and improves performance.

## Implementation

### Step 1: Add `PersonalAccessToken.php`

A `PersonalAccessToken` model has been provided in the repository. Developers need to move this model to the `Models` folder.

### Step 2: Register the Model in `ServiceProvider`

To use the custom `PersonalAccessToken` model, add the following code in your `AppServiceProvider` or any relevant service provider:

```php
\Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(
    \App\Models\PersonalAccessToken::class
);
```

### Step 3: Handling Token Deletion

When revoking a user's access by deleting a token, **invalidate its cache** as well to ensure immediate effect.

---

## Notes
- This approach improves API performance by minimizing redundant database queries.
- Ensure you handle token caching efficiently to prevent stale authentication data.
- Always clear cached tokens when they are revoked to avoid unauthorized access.

---

## Contributing
Feel free to submit issues or pull requests if you have improvements or suggestions!

