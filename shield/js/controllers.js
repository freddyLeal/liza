angular.module('app.controllers', [])

    .controller('siteController',function($scope, $ionicModal, $timeout, $http, $stateParams) {
        $scope.urlBase = "http://localhost/sword/";
        $scope.isLogedState = false;
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////// GENERAL //////////////
        $scope.isLoged = function(){
            $http.post($scope.urlBase+"site/isloged").then(
                function(response){
                    if(response.data.sistemConfirm != true)
                        $scope.login();
                    else 
                        $scope.isLogedState = true;
                }, 
                function(response){ $scope.unexpectedError(); }
            ); 
        };

        $scope.unexpectedError = function(){
            alert("Unexpected error.");
        };


        

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////// FINANCIAL ////////////////////////
        $scope.financialTypes = {};
        $scope.financialTypeForm = {};
        $scope.financialTypeForm.isIncome = "1";
        $scope.financialTypeFormMenssage = "";
        $scope.fromIncomes = true;
        $scope.incomesMessage = "";
        $scope.expensesMessage = "";
        $scope.financialReport = {};
        $scope.registerIncomes = {};
        $scope.registerIncomes.date = new Date();
        $scope.registerExpenses = {};
        $scope.registerExpenses.date = new Date();
        $scope.incomesAndExpenses = {};
        $scope.financialRegister = {};
        $scope.financialRegister.id = $stateParams.fr_id;
        $scope.financialRegister.isIncome = $stateParams.is_income;
        $scope.financialRegisterForEdit = {};



        $scope.getFinancialReport = function(){
            $http.post($scope.urlBase+"financial/getReport").then(
                function(response){
                    if(response.data.sistemConfirm == true){
                        $scope.financialReport = response.data.sistemMessage;
                    } 
                }, 
                function(response){ $scope.unexpectedError(); }
            ); 
        };

        $scope.registerIncome = function(){
            if( $scope.registerIncomes.income != null && $scope.registerIncomes.date != null && $scope.registerIncomes.type != null ){
                $http.post($scope.urlBase+"financial/createIncome", $scope.registerIncomes).then(
                    function(response){
                        if(response.data.sistemConfirm == true){
                            $scope.incomesMessage = "";
                            $scope.getFinancialReport();
                            $scope.registerIncomes = {};
                            $scope.registerIncomes.date = new Date();
                            $scope.registerIncomes.type = $scope.financialTypes.incomes[0].ft_id;
                        } else
                            $scope.incomesMessage = response.data.sistemMessage;
                    },
                    function(response){ $scope.unexpectedError(); }
                );
            } else {
                $scope.incomesMessage = "Value, Date or Type cann't by empty.";
            }
        }

        $scope.registerExpense = function(){
            if( $scope.registerExpenses.expense != null && $scope.registerExpenses.date != null && $scope.registerExpenses.type != null ){
                $http.post($scope.urlBase+"financial/createExpense", $scope.registerExpenses).then(
                    function(response){
                        if(response.data.sistemConfirm == true){
                            $scope.expensesMessage = "";
                            $scope.getFinancialReport();
                            $scope.registerExpenses = {};
                            $scope.registerExpenses.date = new Date();
                            $scope.registerExpenses.type = $scope.financialTypes.expenses[0].ft_id;
                        } else
                            $scope.expensesMessage = response.data.sistemMessage;
                    },
                    function(response){ $scope.unexpectedError(); }
                );
            } else {
                $scope.expensesMessage = "Value, Date or Type cann't by empty.";
            }
        }

        $scope.createFinancialTypes = function (){
            if( $scope.financialTypeForm.typeName != null && $scope.financialTypeForm.isIncome != null ){
                $http.post($scope.urlBase+"financial/createFinancialType", $scope.financialTypeForm).then(
                    function(response){
                        if(response.data.sistemConfirm == true){
                            $scope.financialTypeFormMenssage = "";
                            $scope.getFinancialTypes();
                            $scope.financialTypeForm = {};
                            $scope.financialTypeForm.isIncome = "1";
                        } else
                            $scope.financialTypeFormMenssage = response.data.sistemMessage;
                    },
                    function(response){ $scope.unexpectedError(); }
                );
            }else
                $scope.financialTypeFormMenssage = "Name nor type cannot be empty."
        }

        $scope.getFinancialTypes = function(){
            $http.post($scope.urlBase+"financial/getFinancialTypes", null).then(
                function(response){
                    if(response.data.sistemConfirm == true){
                        $scope.financialTypes = response.data.sistemMessage;
                        $scope.registerIncomes.type = ""+response.data.sistemMessage.incomes[0].ft_id;
                        $scope.registerExpenses.type = ""+response.data.sistemMessage.expenses[0].ft_id;
                    } else {
                        financialTypeFormMenssage = "Not found incomes types or expenses types.";
                    }
                },
                function(response){ $scope.unexpectedError(); }
            );
        }

        $scope.getIncomesAndExpenses = function(){
            $http.post($scope.urlBase+"financial/getIncomesAndExpenses").then(
                function(response){
                    if(response.data.sistemConfirm == true){
                        $scope.incomesAndExpenses = response.data.sistemMessage;
                    }
                },
                function(response){ $scope.unexpectedError(); }
            );
        }

        $scope.getIncomeOrExpense = function(){
            $http.post($scope.urlBase+"financial/getIncomeOrExpense", $scope.financialRegister).then(
                function(response){
                    if(response.data.sistemConfirm == true){
                        $scope.financialRegisterForEdit = response.data.sistemMessage.financialRegister;
                        $scope.financialRegisterForEdit.date = new Date(response.data.sistemMessage.financialRegister.date);
                        $scope.financialRegisterForEdit.typeId = response.data.sistemMessage.financialRegister.typeId;
                        $scope.financialTypes  = response.data.sistemMessage.financialTypes;
                    }
                },
                function(response){ $scope.unexpectedError(); }
            );
        }

        $scope.editIncomeOrExpense = function(){
            $http.post($scope.urlBase+"financial/editIncomeOrExpense", $scope.financialRegisterForEdit).then(
                function(response){
                    if(response.data.sistemConfirm == true)
                        $scope.editIncomeOrExpenseMessage = "The register had be modified successful.";
                    else
                        $scope.editIncomeOrExpenseMessage = response.data.sistemMessage;
                },
                function(response){ $scope.unexpectedError(); }
            );
        }



        $scope.deleteIncomeOrExpense = function(){
             $http.post($scope.urlBase+"financial/deleteIncomeOrExpense", $scope.financialRegisterForEdit).then(
                function(response){
                    if(response.data.sistemConfirm == true)
                        $scope.editIncomeOrExpenseMessage = "The register had be deleted successful.";
                    else
                        $scope.editIncomeOrExpenseMessage = response.data.sistemMessage;
                },
                function(response){ $scope.unexpectedError(); }
            );
        }

        ////////////////// END FINANCIAL ////////////////////









        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////// LOGIN ///////////////////////
        $scope.loginData = {};
        $scope.loginMessage = "";

        $ionicModal.fromTemplateUrl('views/site/login.html', {
            scope: $scope
        }).then(function(modal) {
            $scope.modal = modal;
        });

        $scope.closeLogin = function() {
            $scope.modal.hide();
        };

        $scope.login = function() {
            $scope.modal.show();
        };

        $scope.doLogin = function() {
            if( $scope.loginData.username != null && $scope.loginData.password != null ){
                $http.post($scope.urlBase+"site/login",  $scope.loginData   ).then(
                    function(response){ 
                        if( response.data.sistemConfirm == true){
                            $scope.isLogedState = true;
                            $scope.closeLogin();
                        } else {
                            $scope.loginMessage = response.data.sistemMessage;
                        }
                    },
                    function(response){ $scope.unexpectedError(); }
                );           
            } else {
                $scope.loginMessage = "Username or Password cann't be empty.";
            } 
        };

        $scope.logout = function(){
            $scope.isLogedState = false;
            $http.post($scope.urlBase+"site/logout");
        };
        ////////////////////// END LOGIN ////////////

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////// REGISTER ///////////////////////
        $scope.registerData = {};
        $scope.registerMessage = "";
        

        $ionicModal.fromTemplateUrl('views/site/register.html', {
            scope: $scope
        }).then(function(modal2) {
            $scope.modal2 = modal2;
        });

        $scope.closeRegister = function() {
            $scope.modal2.hide();
        };

        $scope.register = function() {
            $scope.modal2.show();
        };

        $scope.doRegister = function(){
            if( $scope.registerData.name != null && $scope.registerData.email != null && $scope.registerData.password != null && $scope.registerData.verifyPassword != null ){
                $http.post($scope.urlBase+"site/register",  $scope.registerData   ).then(
                    function(response){ 
                        if( response.data.sistemConfirm == true){
                            $scope.registerMessage = "Confirmation email has been sent, please review your inbox.";
                        } else {
                            $scope.registerMessage = response.data.sistemMessage;
                        }
                    },
                    function(response){ $scope.unexpectedError(); }
                );   
            } else {
                $scope.registerMessage = "Form has not been completed.";
            }
        }

        $scope.forgetPassword = function(){

            if( $scope.loginData.username != null){
                $http.post($scope.urlBase+"site/forgetPassword",  $scope.loginData.username   ).then(
                    function(response){ 
                        if( response.data.sistemConfirm == true){
                            $scope.loginMessage = "Confirmation email has been sent, please review your inbox.";
                        } else {
                            $scope.loginMessage = response.data.sistemMessage;
                        }
                    },
                    function(response){ $scope.unexpectedError(); }
                );   
            } else {
                $scope.loginMessage = "Email cannot be empty.";
            }
        }

        ////////////////////// END REGISTER ////////////


    });