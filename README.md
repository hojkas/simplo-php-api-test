# PHP programmer test

The first part is about setup - mostly for me to have future refernce of steps needed to set it up.

In the [second part](##api) you can find description of the endpoints capabilites.

This project is made from a laravel template. I have left a few files not needed in the project that are not needed for the API, but I wasn't sure it wouldn't be hurtful to delete them.

## Setup

To work properly, the app needs file `.env` in the root folder with variables regarding database connection set properly. In the developement process, I used `DB_CONNECTION=sqlite` and `DB_DATABASE=` with full path of sqlite database stored.

Once the db is set up, run `php artisan migrate` to apply migrations and `php artisan db:seed` to seed the data.

Use `php artisan serve` to start the developement server, or `php artisan test` to run tests.

### Test coverage:

```
Http/Controllers\Controller  .............................................................. 100.0 %  
Http/Controllers\CustomerController  ...................................................... 100.0 %  
Http/Resources\CustomerGroupResource  ..................................................... 100.0 %  
Http/Resources\CustomerResource  .......................................................... 100.0 %
Interfaces\CustomerRepositoryInterface  ................................................... 100.0 %  
Models\Customer  .......................................................................... 100.0 %  
Models\CustomerGroup  ..................................................................... 100.0 %   
Repositories\CustomerRepository  .......................................................... 100.0 %
```

## Models

`Customer` has attributes:

- `id`
- `name`
- `surname`
- `email`
- `phone_number`
- `created_at` (cannot be set through api)
- `updated_at` (cannot be set through api)

`CustomerGroup` has attributes:

- `id`
- `name`
- `created_at` (cannot be set through api)
- `updated_at` (cannot be set through api)

## API

Available endpoints:

- `GET api/customers/` Shows all customers (without groups they belong to)
- `POST api/customers/` Creates a new customer
- `GET api/customers/{id}` Shows customer with given id (without groups he belongs to)
- `GET api/customers/{id}?showGroups=true` Shows customer with given id including groups he belongs to
- `PUT api/customers/{id}` Updates customer with given id
- `DELETE api/customers/{id}` Delets customer with given id
- `GET api/customers/{id}/groups` Get list of groups user belongs to
- `PUT api/customers/{id}/groups/{group_id}` Adds a customer with given id to the group with given group_id
- `DELETE api/customers/{id}/groups/{group_id}` Removes customer with given id from the group with given group_id