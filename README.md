### Lottery Service

## Requirements are:
   - The service will allow anyone to register as a lottery participant.
   - Lottery participants will be able to submit as many lottery ballots for any lottery that isn’t yet finished.
   - Each day at midnight the lottery event will be considered closed and a random lottery winner will be selected from all participants for the day.
   - All users will be able to check the winning ballot for any specific date.
   - The service will have to persist the data regarding the lottery.
   
## TODO:
- [x] setup laravel sanctum package for token authorization
- [x] create models, migrations, factories, seed (Ballot, Lottery, Profile, LotteryDraw)
- [ ] create resource-management routes for admin ()
- [x] setup BaseAbstractController with basic CRUD features for admin
- [x] middleware for checking admin role
- [x] setup LotterySelectionDTO for saving the ballot structure
- [ ] create route '/lotteries/{id}/register' for lottery participant
- [ ] create cron job for drawing a winner every day at midnight
