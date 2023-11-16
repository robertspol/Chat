const messageFieldWrapper = document.querySelector('.message-field-wrapper');
const messageField = messageFieldWrapper.querySelector('.message-field');
const messageBtn = messageFieldWrapper.querySelector('button');

const chatWindow = document.querySelector('.chat-window');

const formData = {};
let isScrolled = false;

messageFieldWrapper.addEventListener('submit', e => {
    e.preventDefault();
});

const doObjFromForm = () => {
    for (let i = 0; i < messageFieldWrapper.elements.length; i++) {
        const element = messageFieldWrapper.elements[i];

        if (element.type !== 'submit') {
            formData[element.name] = element.value;
        }
    }
}

messageBtn.addEventListener('click', () => {
    doObjFromForm();

    fetch('insert_messages.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
    })
        .then(res => {
            if (res.status === 200) {
                messageField.value = '';

                scrollToBottom();
            } else {
                throw new Error('Wystąpił błąd podczas przesyłania danych.')
            }
        })
        .catch(err => {
            console.error(err);
            alert(err);
        });
});

chatWindow.addEventListener('mouseenter', () => {
    isScrolled = true;
});

chatWindow.addEventListener('mouseleave', () => {
    isScrolled = false;
});

setInterval(() => {
    doObjFromForm();

    fetch('get_messages.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
    })
        .then(res => {
            if (res.status === 200) {
                return res.text();
            } else {
                throw new Error('Wystąpił błąd podczas przesyłania danych.')
            }
        })
        .then(data => {
            chatWindow.innerHTML = data;

            if (isScrolled === false) {
                scrollToBottom();
            }
        })
        .catch(err => {
            console.error(err);
            alert(err);
        });
}, 500);

const scrollToBottom = () => {
    chatWindow.scrollTop = chatWindow.scrollHeight;
}