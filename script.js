const container = document.querySelector('.products');

const xhr = new XMLHttpRequest();

xhr.open('GET', 'operation.php', true);

xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
        let data = xhr.responseText;
        console.log(data);
		container.innerHTML = data;
    }
};

xhr.send();



