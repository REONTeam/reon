/* What this is:
 *
 * Bootstrap came with JavaScript in addition to styling. This file substitutes
 * that behavior, and may eventually be renamed.
 */

/* Mobile menu button ********************************************************/
document.getElementsByClassName("navbar-toggler")[0].addEventListener("click",
function(){
    // First get a pointer to the navbar menu items
    navItems = document.getElementById("reonNavbar");
    
    // Do the toggle
    isOpen = navItems.classList.contains("show");
    
    if (isOpen) {
        this.classList.add("collapsed");
        navItems.classList.remove("show");
        this.setAttribute("aria-expanded","false");
    } else {
        this.classList.remove("collapsed");
        navItems.classList.add("show");
        this.setAttribute("aria-expanded","true");
    }
});





/* Dropdowns *****************************************************************/
// At most, only one may be open at a time
ddNowOpen = {
    toggle: null,
    list: null
}

function toggleMenu(ddToggle,ddList) {
    isOpen = ddToggle.classList.contains("show");
    
    // First close the currently-open menu
    closeMenu();
    
    // Only open this menu if it wasn't the one that just got closed
    if (!isOpen) {
        ddToggle.classList.add("show");
        ddList.classList.add("show");
        ddToggle.setAttribute("aria-expanded","true");
        
        // "Register" as the currently-open menu
        ddNowOpen.toggle = ddToggle;
        ddNowOpen.list = ddList;
    }
}

function closeMenu() {
    if (ddNowOpen.toggle) {
        ddNowOpen.toggle.classList.remove("show");
        ddNowOpen.list.classList.remove("show");
        ddNowOpen.toggle.setAttribute("aria-expanded","false");
        
        // "De-register" the menu
        ddNowOpen.toggle = null;
        ddNowOpen.list = null;
    }
}



// Actual event-handler assignment
document.body.addEventListener("click",closeMenu);

for (ddParent of document.getElementsByClassName("dropdown")) {
    // First get references to important dropdown parts
    // (scoping is critical here!)
    let ddToggle = ddParent.getElementsByClassName("dropdown-toggle")[0];
    let ddList = ddParent.getElementsByClassName("dropdown-menu")[0];
    
    // Then set up event handling
    ddToggle.addEventListener("click",function(event){
        toggleMenu(ddToggle,ddList);
        event.stopPropagation(); // prevent body's event handler from immediately closing the menu
    });
    
    ddList.addEventListener("click",function(event){
        event.stopPropagation(); // don't close the menu by accident!
    })
}
