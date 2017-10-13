(function () {
    'use strict';

    angular.module('app', [
        'ngStorage',
        'ngRoute',
        'angular-loading-bar'
    ])
        .constant('urls', {
            BASE: 'http://127.0.0.1:8000',
            BASE_API: 'http://127.0.0.1:8000/v1'
        })
        .config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {
            $routeProvider.
                when('/', {
                    templateUrl: 'partials/home.html',
                    controller: 'HomeController'
                }).
                when('/signin', {
                    templateUrl: 'partials/signin.html',
                    controller: 'HomeController'
                }).
                when('/signup', {
                    templateUrl: 'partials/signup.html',
                    controller: 'HomeController'
                }).
                when('/restricted', {
                    templateUrl: 'partials/restricted.html',
                    controller: 'RestrictedController'
                }).
                when('/addapartment', {
                    templateUrl: 'partials/addapartment.html',
                    controller: 'ApartmentController'
                }).
                when('/apartments', {
                    templateUrl: 'partials/apartments.html',
                    controller: 'ApartmentController'
                }).
                when('/apartments/:id/edit', {
                    templateUrl: 'partials/editapartment.html',
                    controller: 'ApartmentEditController'
                    // controller: function ($scope, $routeParams) {
                    //     $scope.id = $routeParams.id;
                    // }
                }).
                when('/delete/:id', {
                    // templateUrl: 'partials/editapartment.html',
                    controller: 'ApartmentDeleteController'
                }).
                otherwise({
                    redirectTo: '/'
                });

            $httpProvider.interceptors.push(['$q', '$location', '$localStorage', function ($q, $location, $localStorage) {
                return {
                    'request': function (config) {
                        config.headers = config.headers || {};
                        if ($localStorage.token) {
                            config.headers.Authorization = 'Bearer ' + $localStorage.token;
                        }
                        return config;
                    },
                    'responseError': function (response) {
                        if (response.status === 401 || response.status === 403) {
                            delete $localStorage.token;
                            $location.path('/signin');
                        }
                        return $q.reject(response);
                    }
                };
            }]);
        }
        ]).run(function($rootScope, $location, $localStorage) {
            $rootScope.$on( "$routeChangeStart", function(event, next) {
                if ($localStorage.token == null) {
                    if ( next.templateUrl === "partials/restricted.html" || next.templateUrl === "partials/addapartment.html") {
                        $location.path("/signin");
                    }
                }
            });
        });
})();