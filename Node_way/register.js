document.addEventListener("DOMContentLoaded", function () {
    const registerBtn = document.getElementById("registerBtn");

    registerBtn.addEventListener("click", function () {
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        console.log("Name:", name);
        console.log("Email:", email);
        console.log("Password:", password);

        // You can add form validation or send data to a backend server here
    });
});
