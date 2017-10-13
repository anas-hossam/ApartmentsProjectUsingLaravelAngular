(function () {
    'use strict';

    angular.module('app')
        .controller('HomeController', ['$rootScope', '$scope', '$location', '$localStorage', 'Auth',
            function ($rootScope, $scope, $location, $localStorage, Auth) {
                function successAuth(res) {
                    $localStorage.token = res.token;
                    window.location = "/";
                }

                $scope.signin = function () {
                    var formData = {
                        email: $scope.email,
                        password: $scope.password
                    };

                    Auth.signin(formData, successAuth, function () {
                        $rootScope.error = 'Invalid credentials.';
                    })
                };

                $scope.signup = function () {
                    var formData = {
                        email: $scope.email,
                        name: $scope.name,
                        password: $scope.password
                    };

                    Auth.signup(formData, successAuth, function (res) {
                        $rootScope.error = res.error || 'Failed to sign up.';
                    })
                };

                $scope.logout = function () {
                    Auth.logout(function () {
                        window.location = "/"
                    });
                };
                $scope.token = $localStorage.token;
                $scope.tokenClaims = Auth.getTokenClaims();
            }])
        .controller('ApartmentController', ['$rootScope', '$scope', '$location', '$localStorage', 'Auth', 'Data','$routeParams',
            function ($rootScope, $scope, $location, $localStorage, Auth, Data, $routeParams) {
                function successAuth(res) {
                    $localStorage.token = res.token;
                    window.location = "/#/apartments";
                }

                Data.getApartments(function (res) {
                    $scope.data = res.data;
                }, function () {
                    $rootScope.error = 'Failed to fetch Apartments.';
                });

                // Data.editApartment(function (res) {
                //     console.log("hereeeeeeeeee");
                //     $scope.data = res.data;
                // }, function () {
                //     $rootScope.error = 'Failed to fetch Apartment.';
                // });

                $scope.addapartment = function () {
                    var formData = {
                        street: $scope.street,
                        postcode: $scope.postcode,
                        town: $scope.town,
                        country: $scope.country,
                        contact_email: $scope.contact_email,
                        move_in: $scope.move_in
                    };

                    Auth.addapartment(formData, successAuth, function (res) {
                        $rootScope.error = res.error || 'Failed to Add Apartment.';
                    })
                };


                $scope.token = $localStorage.token;
                $scope.tokenClaims = Auth.getTokenClaims();
            }])
        .controller('ApartmentEditController', ['$rootScope', '$scope', '$location', '$localStorage', 'Auth', 'Data','$routeParams',
            function ($rootScope, $scope, $location, $localStorage, Auth, Data, $routeParams) {
                function successAuth(res) {
                    $localStorage.token = res.token;
                    window.location = "/#/apartments";
                }
                $scope.id = $routeParams.id;

                Data.editApartment($scope.id,function (res) {
                    $scope.data = res.data;

                    // set values in edit apartment
                    $('#street').val(res.data.apartment.street);
                    $('#postcode').val(res.data.apartment.postcode);
                    $('#town').val(res.data.apartment.town);
                    $('#country').val(res.data.apartment.country);
                    $('#email').val(res.data.apartment.contact_email);
                    $('#move_in').val(res.data.apartment.move_in);

                }, function () {
                    $rootScope.error = 'Failed to fetch Apartment.';
                });

                $scope.updateapartment = function () {
                    var formData = {
                        id: $routeParams.id,
                        street: $('#street').val(),
                        postcode: $('#postcode').val(),
                        town:  $('#town').val(),
                        country: $('#country').val(),
                        contact_email:  $('#email').val(),
                        move_in: $('#move_in').val()
                    };

                    Auth.updateapartment(formData, successAuth, function (res) {
                        $rootScope.error = res.error || 'Failed to Update Apartment.';
                    })
                };

                $scope.token = $localStorage.token;
                $scope.tokenClaims = Auth.getTokenClaims();
            }])

        .controller('ApartmentDeleteController', ['$rootScope', '$scope', '$location', '$localStorage', 'Auth', 'Data','$routeParams',
            function ($rootScope, $scope, $location, $localStorage, Auth, Data, $routeParams) {
                function successAuth(res) {
                    $localStorage.token = res.token;
                    window.location = "/#/apartments";
                }
                $scope.id = $routeParams.id;

                Data.deleteApartment($scope.id,function (res) {
                    $scope.data = res.data;

                }, function () {
                    $rootScope.error = 'Failed to Delete Apartment.';
                });

                $scope.updateapartment = function () {
                    var formData = {
                        id: $routeParams.id,
                        street: $('#street').val(),
                        postcode: $('#postcode').val(),
                        town:  $('#town').val(),
                        country: $('#country').val(),
                        contact_email:  $('#email').val(),
                        move_in: $('#move_in').val()
                    };

                    Auth.updateapartment(formData, successAuth, function (res) {
                        $rootScope.error = res.error || 'Failed to Update Apartment.';
                    })
                };

                $scope.token = $localStorage.token;
                $scope.tokenClaims = Auth.getTokenClaims();
            }])

        .controller('RestrictedController', ['$rootScope', '$scope', 'Data', function ($rootScope, $scope, Data) {

            Data.getRestrictedData(function (res) {
                $scope.data = res.data;
            }, function () {
                $rootScope.error = 'Failed to fetch profile content.';
            });
        }]);
})();