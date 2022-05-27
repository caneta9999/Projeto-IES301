export function navbar() {
    const navbar = document.getElementById("navbar");

    const showMenu = "document.getElementById(`menu`).style.animation = `show-menu 0.5s ease-out 1 forwards`"

    navbar.innerHTML = "<header>" 
        + "<span class='material-icons' id='menu-show' onclick='" + showMenu + "'>menu</span>"
        + "<a href='/projeto-ies301/paginas/index.php'><img class='logo' src='/projeto-ies301/imgs/logo-sistema.png' alt=''></a>"
        + "<form id='formLogout' method='POST' action='/projeto-ies301/paginas/Login/logout.php'>"
        + "<input id='inputSubmit' name='sairSubmit' type='submit' value='Sair da conta'>"
        + "</form>"
        + "</header>"
    return navbar;
}

