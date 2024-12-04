<style>
*,
*::after,
*::before {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
/* the after and before stuff will allow for defaulting when other browsers are used */

html {
  font-size: 62.5%;
}

body { /* the --xxx stuff is like a universal thing that can be called. */
  --light: hsl(220, 50%, 90%);
  --primary: hsl(255, 30%, 55%);
  --focus: #1c1b27;
  --border-color: #2f2e40;
  --global-background: #1c1b27;
  --shadow-1: #201f2c;
  --shadow-2: #201f2c;

  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Open Sans', sans-serif;
  color: var(--light);
  background: var(--global-background);
}

a,
a:link {
  font-family: inherit;
  text-decoration: none;
}
/* inherit takes properties from parent element */

a:focus {
  outline: none;
}

button::-moz-focus-inner {
  border: 0;
}

/* modal */
.modal-container {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 10;

  display: none;
  justify-content: center;
  align-items: center;

  width: 100%;
  height: 100%;

  background: var(--m-background);
}

.modal-container:target {
  display: flex;
}

.modal {
    width: 60rem;
    padding: 4rem 2rem;
    border-radius: 0.8rem;

    color: var(--light);
    background: #252433;
    box-shadow: var(--m-shadow, 0.4rem 0.4rem 10.2rem 0.2rem) var(--shadow-1);
    position: relative;

    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.modal__title {
  font-size: 3.2rem;
}

.modal__text {
  padding: 0 4rem;
  margin-top: 2rem;
  margin-bottom: 0.1rem;

  font-size: 1.6rem;
  line-height: 2;
}

.modal__btn {
  padding: 1rem 1.6rem;
  border: 1px solid var(--border-color);
  border-radius: 100rem;

  color: inherit;
  background: transparent;
  font-size: 1.4rem;
  font-family: inherit;
  letter-spacing: .2rem;

  transition: .2s;
  cursor: pointer;
}

.modal__btn:nth-of-type(1) {
  margin-right: 1rem;
}

.modal__btn:hover,
.modal__btn:focus {
  background: var(--focus);
  border-color: var(--focus);
  transform: translateY(-.2rem);
}

/* link-1 (Rate Lunch! Button) */
.link-1 {
  position: fixed;
  bottom: 2rem;
  right: 2rem;

  font-size: 1.8rem;
  color: var(--light);
  background: #252433;
  box-shadow: -.4rem -.4rem 2.4rem .2rem var(--shadow-1);
  border-radius: 100rem;
  padding: 1.4rem 3.2rem;

  transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth animation */
}

.link-1:hover {
  transform: translateY(-0.5rem) scale(1.05);
  box-shadow: 0 0 3.4rem 0.2rem var(--shadow-2);
  background: var(--focus);
  border-color: var(--focus);

}

.link-1:click {
  transform: translateY(-0.5rem) scale(1.05);
  box-shadow: 0 0 3.4rem 0.2rem var(--shadow-2);
  background: var(--focus);
  border-color: var(--focus);

}

/* link-2 (close button) */
.link-2 {
  width: 4rem;
  height: 4rem;
  border: 1px solid var(--border-color);
  border-radius: 100rem;

  color: inherit;
  font-size: 2.2rem;

  position: absolute;
  top: 2rem;
  right: 2rem;

  display: flex;
  justify-content: center;
  align-items: center;

  transition: .2s;
}

.link-2::before {
  content: '×';

  transform: translateY(-.1rem);
}

.link-2:hover,
.link-2:focus {
  background: var(--focus);
  border-color: var(--focus);
  transform: translateY(-.2rem);
}
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    align-items: center;
    margin: 2rem auto;
    width: 100%;
    margin-bottom: 2.5rem;
}

.rating label {
    font-size: 3rem;
    color: #ccc;
    cursor: pointer;
    transition: color 0.3s ease;
}

.rating input {
    display: none; /* Hide the radio buttons */
}

.rating label:hover,
.rating label:hover ~ label {
    color: gold;
    transform: translateY(-.2rem);
    transition: transform 0.1s;
}

.rating input:checked ~ label {
    color: gold;
}

</style>

<!-- Modal Trigger -->
<a href="#m1-o" class="link-1" id="m1-c">Rate Lunch!</a>

<!-- Modal Content -->
<div class="modal-container" id="m1-o" style="--m-background: transparent;">
    <div class="modal">
        <h1 class="modal__title">Rate Today's Lunch!</h1>
        <p class="modal__text">We would love to see your rating. Pick a star from 1-5!</p>

        <!-- Rating Form -->
        <form action="process.php" method="POST" id="rating-form">
            <div class="rating">
                <input type="radio" name="rating" id="star5" value="5">
                <label for="star5">&#9733;</label>
                <input type="radio" name="rating" id="star4" value="4">
                <label for="star4">&#9733;</label>
                <input type="radio" name="rating" id="star3" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" name="rating" id="star2" value="2">
                <label for="star2">&#9733;</label>
                <input type="radio" name="rating" id="star1" value="1">
                <label for="star1">&#9733;</label>
            </div>
            <button type="submit" class="modal__btn">Submit Rating</button>
        </form>

        <a href="#m1-c" class="link-2"></a>
    </div>
</div>

<script>
document.getElementById('rating-form').addEventListener('submit', function (event) {
  event.preventDefault();

  var form = new FormData(this);

  fetch('process.php', {
    method: 'POST',
    body: form
  })
  .then((response) => response.json())
  .then((data) => {
    const averageRating = data.averageRating || 0;

    // Update the modal content
    document.querySelector('.modal__title').textContent = 'Thanks!';
    document.querySelector('.modal__text').innerHTML = `Your feedback has been recorded. The average rating for today's lunch is: <strong>${averageRating.toFixed(2)}</strong> stars.`;

    // Show the "Thank you" message and hide the rating form
    document.getElementById('rating-form').style.display = 'none';
    document.querySelector('.modal__text').style.display = 'block';
  })
  .catch((error) => {
    console.error('Error:', error);
    // Handle errors, such as displaying an error message to the user
  });
});
</script>
