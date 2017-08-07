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
            var element = document.querySelector('#required li:nth-child(1)');
            if (loginInput.Validation.invalidities.length == 0 && passwordInput.Validation.invalidities.length == 0) {
                element.classList.add("valid");
                element.classList.remove("invalid");
            } else {
                element.classList.add("invalid");
                element.classList.remove("valid");
            }
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
    // кол-во символов - не меньше 1
    {
        isInvalid: function(input) {
            return input.value.length < 3;
        },
        invalidityNumber: '1',
    },
];

var passwordValidityChecks = [
    // кол-во символов - не меньше 1
    {
        isInvalid: function(input) {
            return input.value.length < 3;
        },
        invalidityNumber: '1',
    },
];

/* ----------------------------
 Выбираем все input, для которых надо проверить валидность
 Свойству Validation присваиваем класс Validation
 В массив validityChecks записываем все условия проверки на валидность
 ---------------------------- */

var loginInput = document.getElementById('login');
var passwordInput = document.getElementById('password');

loginInput.Validation = new Validation(loginInput);
loginInput.Validation.validityChecks = loginValidityChecks;

passwordInput.Validation = new Validation(passwordInput);
passwordInput.Validation.validityChecks = passwordValidityChecks;


/* ----------------------------
 Валидация при попытке отправки формы
 ---------------------------- */

var inputs = document.querySelectorAll('input:not([type="submit"])');


var submit = document.querySelector('input[type="submit"]');
var form = document.getElementById('login_form');

function validate() {
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].Validation.checkInput();
    }
}

submit.addEventListener('click', validate);
form.addEventListener('submit', validate);



