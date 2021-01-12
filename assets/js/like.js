const axios = require('axios')

document.querySelectorAll('a.btn-like').forEach(link => {
  link.addEventListener('click', event => {
      event.preventDefault()

      const postLikes = link.parentNode.querySelector('span.post-likes')
      const icon = link.querySelector('svg')
      
      axios.get(link.href).then(response => {
          postLikes.textContent = `${response.data.likes} ${response.data.likes > 1 ? 'likes' : 'like'}`

          if (icon.classList.contains('text-red-500') && icon.getAttribute('fill') === 'currentColor') {
              icon.classList.remove('text-red-500')
              icon.setAttribute('fill', 'none')
          } else {
              icon.classList.add('text-red-500')
              icon.setAttribute('fill', 'currentColor')
          }
      }).catch(error => {
          if (error.response.status === 403) {
              console.log(error.response.message)
          } else {
              console.log('Impossible de liker le post')
          }
      })
  })
})