const usersList = document.querySelector('.users-list');

const searchWrapper = document.querySelector('.search');

const searchInput = document.querySelector('.search input')
const searchBtn = document.querySelector('.search button');

const accountDelete = document.querySelector('.account-delete');

searchInput.addEventListener('keyup', () => {
    const searchTerm = searchInput.value;

    if (searchTerm !== '') {
        searchInput.classList.add('active');
    } else {
        searchInput.classList.remove('active');
    }

    fetch('search.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "search_term=" + searchTerm,
    })
        .then(res => {
            if (res.status === 200) {
                return res.text();
            } else {
                throw new Error('Wystąpił błąd podczas przesyłania danych.')
            }
        })
        .then(data => usersList.innerHTML = data)
        .catch(err => {
            console.error(err);
            alert(err);
        });
});

accountDelete.addEventListener('click', () => {
    const confirmation = confirm('Czy na pewno chcesz usunąć konto?');

    if (confirmation) {
        location.href = 'account_delete.php';
    } else return;
});

// setInterval(() => {
fetch('users_backend.php')
    .then(res => {
        if (res.status === 200) {
            return res.text();
        } else {
            throw new Error('Wystąpił błąd podczas przesyłania danych.')
        }
    })
    .then(data => {
        if (!searchInput.classList.contains('active')) {
            usersList.innerHTML = data;
        }
    })
    .catch(err => {
        console.error(err);
        alert(err);
    });
// }, 500);