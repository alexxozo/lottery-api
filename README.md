# Lottery Service

## Description:
The project simulates a lottery API service that a real lottery business can use. It is capable of registering new users, allow them to participate to
different lotteries and each day a lottery draw is made. The system is meant to be very flexible regarding the types of lotteries that can be created, at the moment
there is only one type of lottery, 6/49 which means choosing 6 numbers from 1-49 range. Unfortunately, because of time limits
I was not able to implement other types of lotteries with different restrictions but this could be possible. Therefore, the odds of actually winning this type of lottery in a day are really low,
for testing we can change a bit the code to simulate a winning. (or who knows, maybe we get lucky :D)

## Requirements:
- [x] The service will allow anyone to register as a lottery participant -> create account at '/register' + '/login
- [x] Lottery participants will be able to submit as many lottery ballots for any lottery that isn’t yet finished. -> use '/lotteries/{id}/register' to add a buy a ballot for lottery
- [x] Each day at midnight the lottery event will be considered closed and a random lottery winner will be selected from all participants for the day. -> command 'draw:today_winner' is scheduled to run every day at 00:00 for every lottery that is not expired -- a cronjob needs to be created on the deployment
- [x] All users will be able to check the winning ballot for any specific date -> use '/lotteries/{id}/winner-ballot' with query string '?year=&month=&day=
- [x] The service will have to persist the data regarding the lottery -> db is persistent on running the docker container
   
## Extra requirements:
- [x] The service will be flexible enough to accept different types of lottery rules
- [x] Basic lottery rules will be based on 6/49 lottery (choosing 6 numbers from the range 1-49)
- [x] The service will notify (mail, database) all the users participating in a lottery, with a win/lose message about the current draw
- [x] A participant will have a "balance" that contains a finite number of units he can spend on ballots, when he wins it will get those units from the price added to his balance  
   
## TODO:
- [x] setup laravel sanctum package for token authorization
- [x] create models, migrations, factories, seeder (Ballot, BalllotNumber, Lottery, Profile, User, LotteryDraw, LotteryDrawNumber, LotteryWinner)
- [x] create resource-management routes for admin (Ballot, Lottery, Profile, User)
- [x] setup BaseAbstractController with basic CRUD features for admin (can be extended for any model)
- [x] middleware for checking admin role (used for resource-management routes)
- [x] create route '/lotteries/{id}/register' for registering lottery participant
- [x] create route '/lotteries/{id}/winner-ballot' for getting the winner ballot in a specific day
- [ ] create general testcases for CRUD routes
- [ ] create test for '/lotteries/{id}/register'
- [ ] create test for '/lotteries/{id}/winner-ballot'
- [x] create job for drawing a winner every day at midnight
- [ ] setup cronjob in docker instructions
- [ ] create index for columns which are most queried
- [x] create task for dispatching job (php artisan draw:today_winner)
- [x] email/db notification when there is a new event for a lottery (winners/losers events)

## Required to run:
- docker running in the background

## How to run the project locally:
- run in main folder the command ```./vendor/bin/sail up```
- create a new .env file by duplicating .env.example
- run migrations and seed using ```php artisan migrate:fresh --seed```
- if migrations don't work please switch between these 2 lines in the .env file (hopefully this won't be necessary in next update): 
```
# For migrations comment this
DB_HOST=mysql
# For migrations uncomment this
#DB_HOST=127.0.0.1
```
- if everything is set without problems, go ahead and test the routes using the postman folder
- to fake a random draw that happens at midnight we can just run ```php artisan draw:randomWinner```

    this command will run our SelectWinnerJob and will create an entry in lottery_draws with the corresponding draw -- for more info about the draw we can inspect logs/laravel.log where something like the following messages can be seen 
```
[2021-07-25 21:53:36] local.INFO: A new random draw has been triggered at: 2021-07-25 21:53:36 (draw_id: 5, numbers: [1,2,3,4,5,6])   
[2021-07-25 21:53:36] local.INFO: There is no winner :(. (draw_id: 5, numbers: [1,2,3,4,5,6])   
```
also there is info about draws in the notifications table for a specific user, since users are notified everytime a draw takes place.


## For postman:
1. import postman collection from './postman/postman_collection.json'
2. import postman environment from './postman/postman_environment.json'
3. use the login routes to get a personal access token that will be set automatically as the "token" variable in the env settings
, this will be used as a Bearer token for the 2 Folders "Participant Routes" / "Admin Routes"
