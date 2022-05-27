export function menu() {
    const menu = document.getElementById("menu");

    const hideMenu = "document.getElementById(`menu`).style.animation = `hide-menu 0.5s ease-out 1 forwards`;"

    const btnUsuarios = menu.classList.contains('menu-adm') ? '<a href="/projeto-ies301/paginas/Usuarios"><span class="material-icons menu-button">person</span> Usuários</a>' : '';

    menu.innerHTML = 
        '<div class="menu-hide-container"><span id="menu-hide" class="material-icons" onclick="' + hideMenu + '">menu</span></div>'
     +  btnUsuarios
     +  '<a href="/projeto-ies301/paginas/Cursos"><span class="material-icons menu-button">school</span> Cursos</a>'
     +  '<a href="/projeto-ies301/paginas/Disciplinas"><span class="material-icons menu-button">menu_book</span> Disciplinas</a>'
     +  '<a href="/projeto-ies301/paginas/Criticas"><span class="material-icons menu-button">star</span> Críticas</a>'
}