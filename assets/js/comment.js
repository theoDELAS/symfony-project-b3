const axios = require('axios')

const addCommentInList = (list, username, comment) => {
  const commentBloc = document.createElement('div')
  const usernameParagraph = document.createElement('p')
  const commentParagraph = document.createElement('p')

  commentBloc.setAttribute('class', 'flex px-3')
  usernameParagraph.setAttribute('class', 'font-medium mr-2')

  usernameParagraph.textContent = username
  commentParagraph.textContent = comment

  commentBloc.appendChild(usernameParagraph)
  commentBloc.appendChild(commentParagraph)
  list.prepend(commentBloc)
}

document.querySelectorAll('.post-card').forEach(card => {
  const commentsList = card.querySelector('.comments-list')
  const commentLikes = card.querySelector('.comment-likes')

  card.querySelectorAll('.form-comment').forEach(form => {
    form.addEventListener('submit', event => {
      event.preventDefault()
  
      const textarea = form.querySelector('textarea')
      const token = form.querySelector("input[name='token']")
      const username = form.querySelector("input[name='username']")
      
      const headers = {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      }
  
      axios.post(form.action, {
        comment: textarea.value,
        token: token.value
      }, headers).then(response => {
        addCommentInList(commentsList, username.value, textarea.value)
        if (response.data.comments >= 1) {
          card.querySelector('.comment-separator').classList.remove('hidden')
        }
        commentLikes.textContent = `${response.data.comments} ${response.data.comments > 1 ? 'commentaires' : 'commentaire'}`
        textarea.value = ''
      }).catch(error => {
        if (error.status === 403) {
          console.log(error.message)
        } else {
          console.log('Impossible de commenter ce post')
        }
      })
    })
  })
})