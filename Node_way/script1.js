document.addEventListener("DOMContentLoaded", function () {
    const loginButton = document.querySelector(".login button");
    document.getElementById("goRegister").addEventListener("click", function () {
    window.location.href = "register.html";
});


    loginButton.addEventListener("click", function () {
        const email = document.querySelector('.login input[type="email"]').value;
        const password = document.querySelector('.login input[type="password"]').value;

        // Log values to console (you can handle them differently, like sending to server)
        console.log("Email:", email);
        console.log("Password:", password);

        // Optional: Prevent form submission if inside a <form>
        // event.preventDefault();
    });
});
