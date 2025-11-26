/* JavaScript minimal pour le Memory game */
(() => {
  const board = document.getElementById('memory-board');
  if (!board) return;

  const deck = window.DECK || JSON.parse(board.dataset.deck || '[]');
  const pairs = window.PAIRS || deck.length / 2;

  // adapt grid columns based on card count
  const cols = Math.min(6, Math.ceil(Math.sqrt(deck.length)));
  board.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;

  let first = null;
  let second = null;
  let moves = 0;
  let matches = 0;
  let timer = 0;
  let interval = null;

  function startTimer() {
    interval = setInterval(() => {
      timer++;
      document.getElementById('timer').textContent = timer;
    }, 1000);
  }

  function stopTimer() { if (interval) clearInterval(interval); }

  function updateMoves() { document.getElementById('moves').textContent = moves; }

  function cardClick(e) {
    const el = e.currentTarget;
    if (el.classList.contains('flipped') || el.classList.contains('matched')) return;
    if (!interval) startTimer();

    el.classList.add('flipped');
    // reveal the card's value (emoji or symbol)
    el.textContent = el.dataset.value;
    const val = el.dataset.value;
    if (!first) {
      first = el; return;
    }
    if (first === el) return;
    second = el;
    moves++;
    updateMoves();

    if (first.dataset.value === second.dataset.value) {
      // matched
      setTimeout(() => {
        first.classList.add('matched');
        second.classList.add('matched');
        first = null; second = null;
        matches++;
        if (matches >= pairs) finishGame();
      }, 400);
    } else {
      setTimeout(() => {
        first.classList.remove('flipped');
        second.classList.remove('flipped');
        // hide values again
        first.textContent = '';
        second.textContent = '';
        first = null; second = null;
      }, 700);
    }
  }

  function finishGame() {
    stopTimer();
    // envoyer score au serveur via redirection GET (simple)
    const player = encodeURIComponent(window.PLAYER || 'Guest');
    const url = `/game/save?player=${player}&pairs=${pairs}&time=${timer}&moves=${moves}`;
    // slight delay to let animation finish
    setTimeout(() => { window.location.href = url; }, 700);
  }

  // create cards
  deck.forEach((v, idx) => {
    const c = document.createElement('div');
    c.className = 'card';
    c.dataset.value = v;
    c.dataset.index = idx;
    c.textContent = '';
    c.addEventListener('click', cardClick);
    board.appendChild(c);
  });

})();
