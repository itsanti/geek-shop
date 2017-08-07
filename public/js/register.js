// валидация input
function Validation(input) {
    // список непрошедших проверок
    this.invalidities = [];
    // список проверок валидности
    this.validityChecks = [];
    //указатель на поле input
    this.inputNode = input;
    // включить слушателя события
    this.attachListener();
}
// общие методы
Validation.prototype = {
    // добавить номер непрошедшей проверки в список
    addInvalidity: function(number) {
        this.invalidities.push(number);
    },
    // проверить все условия на валидность
    checkValidity: function(input) {
        for ( var i = 0; i < this.validityChecks.length; i++ ) {

            var isInvalid = this.validityChecks[i].isInvalid(input);
            // формируем список непрошдих проверок
            if (isInvalid) {
                this.addInvalidity(this.validityChecks[i].invalidityNumber);
            }

            var requirementElement = this.validityChecks[i].element;
            // назначение класса для дальнейшего подкрашивания li в соответсвующий цвет в css
            if (requirementElement) {
                if (isInvalid) {
                    requirementElement.classList.add('invalid');
                    requirementElement.classList.remove('valid');
                } else {
                    requirementElement.classList.remove('invalid');
                    requirementElement.classList.add('valid');
                }
            }
        }
    },
    // проверка input на валидность
    checkInput: function() {
        // обнуляем список непрошедших проверок
        this.inputNode.Validation.invalidities = [];
        // выполняем валидацию
        this.checkValidity(this.inputNode);
        // считаем кол-во непрошедших проверок
        // назначение класса для дальнейшего подкрашивания input border в соответсвующий цвет в css
        if(this.inputNode.Validation.invalidities.length !== 0) {
            this.inputNode.classList.add('invalid');
            this.inputNode.classList.remove('valid');
            // предотвращение отправки формы
            event.preventDefault();
        } else {
            this.inputNode.classList.add('valid');
            this.inputNode.classList.remove('invalid');
        }
    },
    // слушатель, вызывается после ввода каждого символа
    attachListener: function() {
        var Validation = this;
        this.inputNode.addEventListener('keyup', function() {
            Validation.checkInput();
        });
    }

};



/* ----------------------------
 Условия валидации
 (массив проверок валидации для каждого input)
 Каждая проверка состоит из 3 частей:
 1. isInvalid() - сама функция, выполняющая валидность
 2. invalidityNumber - номер проверки на валидность
 3. element - элемент li, соответствующий проверке
 ---------------------------- */

var loginValidityChecks = [
    // кол-во символов - не меньше 3
    {
        isInvalid: function(input) {
            return input.value.length < 3;
        },
        invalidityNumber: '1',
        element: document.querySelector('label[for="login"] .input-requirements li:nth-child(1)')
    },
    // кол-во символов - не больше 32
    {
        isInvalid: function(input) {
            return input.value.length > 32;
        },
        invalidityNumber: '2',
        element: document.querySelector('label[for="login"] .input-requirements li:nth-child(2)')
    },
    // только буквы
    {
        isInvalid: function(input) {
            var illegalCharacters = input.value.match(/[^a-z\s]/ig);
            return !!illegalCharacters; //return illegalCharacters ? true : false;
        },
        invalidityNumber: '3',
        element: document.querySelector('label[for="login"] .input-requirements li:nth-child(3)')
    },
];

var passwordValidityChecks = [
    // кол-во символов - не меньше 4
    {
        isInvalid: function(input) {
            return input.value.length < 4;
        },
        invalidityNumber: '1',
        element: document.querySelector('label[for="password"] .input-requirements li:nth-child(1)')
    },
    // кол-во символов - не больше 32
    {
        isInvalid: function(input) {
            return input.value.length > 32;
        },
        invalidityNumber: '2',
        element: document.querySelector('label[for="password"] .input-requirements li:nth-child(2)')
    },
    // как минимум 1 заглавная, 1 цифра
    {
        isInvalid: function(input) {
            return !input.value.match(/[\dA-Z]+/g);
        },
        invalidityMessage: '1',
        element: document.querySelector('label[for="password"] .input-requirements li:nth-child(3)')
    }
];

var password2ValidityChecks = [
    // Пароли должны совпадать
    {
        isInvalid: function() {
            return password2Input.value != passwordInput.value;
        },
        invalidityMessage: '1',
        element: document.querySelector('label[for="password2"] .input-requirements li:nth-child(1)')
    }
];


/* ----------------------------
 Выбираем все input, для которых надо проверить валидность
 Свойству Validation присваиваем класс Validation
 В массив validityChecks записываем все условия проверки на валидность
 ---------------------------- */

var loginInput = document.getElementById('login');
var passwordInput = document.getElementById('password');
var password2Input = document.getElementById('password2');

loginInput.Validation = new Validation(loginInput);
loginInput.Validation.validityChecks = loginValidityChecks;

passwordInput.Validation = new Validation(passwordInput);
passwordInput.Validation.validityChecks = passwordValidityChecks;

password2Input.Validation = new Validation(password2Input);
password2Input.Validation.validityChecks = password2ValidityChecks;




/* ----------------------------
 Валидация при попытке отправки формы
 ---------------------------- */

var inputs = document.querySelectorAll('input:not([type="submit"])');


var submit = document.querySelector('input[type="submit"]');
var form = document.getElementById('register');

function validate() {
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].Validation.checkInput();
    }
}

submit.addEventListener('click', validate);
form.addEventListener('submit', validate);
