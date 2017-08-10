export default {
  init () {

    let navOpen = false
    const navButtons = document.querySelectorAll('.tke-nav-button')
    const navDrawer = document.querySelector('.tke-nav-drawer')

    navButtons.forEach(navButton => {
      navButton.addEventListener('click', () => {
        navOpen = !navOpen
        if (navOpen) {
          navDrawer.classList.add('tke-open')
          navButton.classList.add('tke-open')
        } else {
          navDrawer.classList.remove('tke-open')
          navButton.classList.remove('tke-open')
        }
      })
    })

  },
  finalize () {

  },
}
