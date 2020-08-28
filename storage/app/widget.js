const styles = '.widget-container { max-width: 375px;  } .form-container-widget { display: flex; } .input {  padding: 10px; border: solid 1px; border-radius: 4px;} .form-container-widget button { box-shadow: none; display: inline-block; font - size: 14px; font - weight: bold; font-style: normal; text - transform: uppercase; } .btn-widget { color: #fff !important; background-color: #0C7EF7 !important; border-radius: 4px; border: none; margin-left: 16px; letter-spacing: 0.2px; padding: 14px} .text{width: 100%;} .success-container{ display: flex; align-items: center; padding: 0 20px;} .ml-10 {margin-left: 10px;}'
const widgetId = '%%%WIDGET_ID%%%';
const url = '%%%URL_SITE%%%/api/widget/data'

/*******************************************************
 * create a filter that will be used to determine
 * which keystrokes are allowed in the input field
 * and which are not. Since we're working exclusively
 * with phone numbers, we'll need the following:
 * -- digits 0 to 9 from the numeric keys
 * -- digits 0 to 9 from the num pad keys
 * -- arrow keys (left/right)
 * -- backspace / delete for correcting
 * -- tab key to allow focus to shift
 *******************************************************/
const filter = []

//since we're looking for phone numbers, we need
//to allow digits 0 - 9 (they can come from either
//the numeric keys or the numpad)
const keypadZero = 48;
const numpadZero = 96;

//add key codes for digits 0 - 9 into this filter
for (let i = 0; i <= 9; i++) {
    filter.push(i + keypadZero);
    filter.push(i + numpadZero);
}

//add other keys that might be needed for navigation
//or for editing the keyboard input
filter.push(8); //backspace
filter.push(9); //tab
filter.push(46); //delete
filter.push(37); //left arrow
filter.push(39); //right arrow

/*******************************************************
 * replaceAll
 * returns a string where all occurrences of a
 * string 'search' are replaced with another
 * string 'replace' in a string 'src'
 *******************************************************/
function replaceAll(src, search, replace) {
    return src.split(search).join(replace);
}

/*******************************************************
 * formatPhoneText
 * returns a string that is in XXX-XXX-XXXX format
 *******************************************************/
function formatPhoneText(value) {
    value = this.replaceAll(value.trim(), " ", "");

    if (value.length > 3 && value.length <= 6)
        value = value.slice(0, 3) + " " + value.slice(3);
    else if (value.length > 6)
        value = value.slice(0, 3) + " " + value.slice(3, 6) + " " + value.slice(6);

    return value;
}

/*******************************************************
 * validatePhone
 * return true if the string 'p' is a valid phone
 *******************************************************/
function validatePhone(p) {
    const phoneRe = /^[(]?[0-9]{3}[)]?[-\s.]?[0-9]{3}[-\s.]?[0-9]{3}$/
    const digits = p.replace(/\D/g, '')
    return phoneRe.test(digits);
}

/*******************************************************
 * onKeyDown(e)
 * when a key is pressed down, check if it is allowed
 * or not. If not allowed, prevent the key event
 * from propagating further
 *******************************************************/
function onKeyDown(e) {
    if (filter.indexOf(e.keyCode) < 0) {
        e.preventDefault();
        return false;
    }
}

/*******************************************************
 * onKeyUp(e)
 * when a key is pressed up, grab the contents in that
 * input field, format them in line with XXX-XXX-XXXX
 * format and validate if the text is infact a complete
 * phone number. Adjust the border color based on the
 * result of that validation
 *******************************************************/
function onKeyUp(e) {
    const input = e.target
    const formatted = formatPhoneText(input.value)
    const isError = (validatePhone(formatted) || formatted.length === 0)
    input.style.borderColor = (isError) ? 'gray' : 'red';
    input.style.outline = 'none';
    input.value = formatted;
}

/*******************************************************
 * setupPhoneFields
 * Now let's rig up all the fields with the specified
 * 'className' to work like phone number input fields
 *******************************************************/
function setupPhoneFields(className) {
    const lstPhoneFields = document.getElementsByClassName(className)

    for (let i = 0; i < lstPhoneFields.length; i++) {
        const input = lstPhoneFields[i]
        if (input.type.toLowerCase() === "text") {
            input.placeholder = "Nro de teléfono";
            input.addEventListener("keydown", onKeyDown);
            input.addEventListener("keyup", onKeyUp);
        }
    }
}

function addStyle(styles) {
    const css = document.createElement('style')
    css.type = 'text/css';
    if (css.styleSheet)
        css.styleSheet.cssText = styles;
    else
        css.appendChild(document.createTextNode(styles));
    document.getElementsByTagName("head")[0].appendChild(css);
}

const render = () => {
    const widget = document.querySelector('#widget-vendedores');
    widget.innerHTML = `
  <div class='widget-container' id='widget-form'>
    <p class='text'>Solicitar mas información o llamada de un agente de ventas</p>
    <div class='form-container-widget'>
      <input id='phone' name="phone" type='text' placeholder="Nro de telefono" required class='input'>
      <button id='contact-btn' class='btn-widget'>Contactarme</button>
    </div>
  </div>
  `
}

const post = () => {
    let phone = document.querySelector('#phone').value
    phone = replaceAll(phone.trim(), " ", "");
    console.log(phone);
    console.log(widgetId);
    fetch(url, {
        method: 'post',
        headers: {
            widgetId,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            phone
        })
    }).then(response => {
        if (response.ok) return renderSuccess()
        throw new Error('Por favor intenta nuevamente');
    }).catch(error => {
        alert('Por favor, intente nuevamente.')
    })
}

const renderSuccess = () => {
    const form = document.querySelector('#widget-form');
    form.style.display = 'none';

    const widget = document.querySelector('#widget-vendedores');
    widget.innerHTML = `
    <div class='widget-container'>
      <div class='success-container'>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="12" fill="#83CC5E"/>
          <path d="M7.05884 12L10.353 15.5294L16.9412 8.47058" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <p class='ml-10'>Tu solicitud ha sido registrada</p>
      </div>
    </div>
  `
}

window.onload = function () {
    addStyle(styles);
    render();
    setupPhoneFields("input");
    document.querySelector('#contact-btn').addEventListener('click', () => {
        post()
    })
}
