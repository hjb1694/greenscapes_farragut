const contactForm = document.querySelector('#contact-form');
const errbox = document.querySelector('.errbox');

const validate = fields => {
    const errors = [];
    errbox.innerHTML = '';
    errbox.classList.remove('show');

    (stringLength(fields.firstName.value.trim()) < 2) && errors.push('Please enter your first name.');
    (stringLength(fields.lastName.value.trim()) < 2) && errors.push('Please enter your last name.');
    (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fields.email.value.trim())) && errors.push('Please enter a valid email address.');
    (stringLength(fields.phone.value) && !/^\([0-9]{3}\) [0-9]{3}\-[0-9]{4}$/.test(fields.phone.value)) && errors.push('Please check the phone number you entered.');
    (stringLength(fields.message.value.trim()) < 10) && errors.push('Please enter a longer message.');

    if(errors.length){
        for(let error of errors){
            errbox.insertAdjacentHTML('beforeend', `<li><img src="/public/icons/error_circle_icon.svg" alt="error icon" /><span>${error}</span></li>`);
        }
        errbox.classList.add('show');
    }

    return !!!errors.length;


}

const submitForm = evt => {
    evt.preventDefault();

    const fields = {
        firstName: document.querySelector('#first-name'),
        lastName: document.querySelector('#last-name'),
        email: document.querySelector('#email'),
        phone: document.querySelector('#phone'),
        canText: document.querySelector('#can-text'),
        message: document.querySelector('#message')
    }

    if(!validate(fields)){
        return;
    }

    try{

        const fd = new FormData();
        fd.append('first_name', fields.firstName.value.trim());
        fd.append('last_name', fields.lastName.value.trim());
        fd.append('email', fields.email.value.trim());
        fd.append('message', fields.message.value.trim());

        if(fields.phone.value.trim()){
            fd.append('phone', fields.phone.value);
            fd.append('can_text', +fields.canText.checked);
        }

        const response = await fetch('/contact', {
            method: 'POST',
            mode: 'no-cors',
            headers: {
                'Accept': 'application/json'
            },
            body: fd
        });

        if(!response.ok){
            throw new Error();
        }
    

    }
    catch(err){
        console.error(err);
        errbox.innerHTML = '';
        errbox.insertAdjacentHTML('beforeend', '<li><img src="/public/icons/error_circle_icon.svg" alt="error icon" /><span>An error has occurred.</span></li>');
        errbox.classList.add('show');
    }
}

contactForm.addEventListener('submit', submitForm);

