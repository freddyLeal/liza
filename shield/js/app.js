// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('app', ['ionic', 'app.controllers'])

.run(function($ionicPlatform) {
    $ionicPlatform.ready(function() {
        // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
        // for form inputs)
        if (window.cordova && window.cordova.plugins.Keyboard) {
            cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
            cordova.plugins.Keyboard.disableScroll(true);
        }
        if (window.StatusBar) {
            // org.apache.cordova.statusbar required
            StatusBar.styleDefault();
        }
    });
})

.config(function($stateProvider, $urlRouterProvider) {
    $stateProvider
    .state('app', {
        url: '/app',
        abstract: true,
        templateUrl: 'views/site/menu.html',
        controller: 'siteController'
    })
    .state('app.login',{
        url:'/login',
        views: {
            'menuContent':{
                templateUrl: 'views/site/login.html',
                controller: 'siteController'
            }
        }
    })
    .state('app.register',{
        url:'/register',
        views: {
            'menuContent':{
                templateUrl: 'views/site/register.html',
                controller: 'siteController'
            }
        }
    })
    .state('app.financialTypes',{
        url:'/financialTypes',
        views: {
            'menuContent':{
                templateUrl: 'views/site/financial_types.html',
                controller: 'siteController'
            }
        }
    })
    .state('app.viewIncomesAndExpenses',{
        url:'/viewIncomesAndExpenses',
        views: {
            'menuContent':{
                templateUrl: 'views/site/view_incomes_and_expenses.html',
                controller: 'siteController'
            }
        }
    })
    .state('app.editIncomeOrExpense',{
        url:'/editIncomeOrExpense/:fr_id/:is_income',
        views: {
            'menuContent':{
                templateUrl: 'views/site/edit_income_or_expense.html',
                controller: 'siteController'
            }
        }
    })

    .state('app.home',{
        url:'/home',
        views: {
            'menuContent':{
                templateUrl: 'views/site/home.html',
                controller: 'siteController'
            }
        }
    });
    $urlRouterProvider.otherwise('/app/home');
});
