export function navbar() {
    const navbar = document.getElementById("navbar");

    navbar.innerHTML = "<header>" 
        + "<a href='/projeto-ies301/paginas/index.php'><img class='logo' src='/projeto-ies301/imgs/logo-sistema.png' alt=''></a>"
        + "<form id='formLogout' method='POST' action='/projeto-ies301/paginas/Login/logout.php'>"
        + "<input id='inputSubmit' name='sairSubmit' type='submit' value='Sair da conta'>"
        + "</form>"
        + "</header>"
    return navbar;
}

