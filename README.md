## Description

- `AbTest` and `AbTestVariant` models implemented to handle db responsibilities.
- `AbTestService` implemented to handle logical responsibilities.
- `AssignAbTestVariant` middleware implemented to choose different strategies for assigning variants.
- `AbstractAssignVariantStrategy`, `AssignVariantStrategyInterface`, `AssignVariantForNewSession` and `AssignVariantForExistingSession` implemented using **Strategy Design Pattern** to handle assigning a variant the session.
- `StartAbTest`, `StopAbTest` and `ViewAbTest` console commands implemented to manage A/B tests.
- `home.blade`, `HomeController` and related route implemented to use A/B test feature.


## How to use:

- run `php artisan migrate`
- run `php artisan ab-test:start FeatureA VariantA:1 VariantB:2`
- open `localhost/project-name/public/home` in the browser to demonstrate the A/B test feature.

Also, you may start another A/B test with its variants and check the url for newly added feature simultaneously.

