const form = document.getElementById('withdrawForm');
const amountInput = document.getElementById('amount');
const feeOutput = document.getElementById('feeOutput');
const modal = document.getElementById('confirmModal');
const confirmYes = document.getElementById('confirmYes');
const confirmNo = document.getElementById('confirmNo');

const loaderScreen = document.getElementById('loaderScreen');
const progressBar = document.getElementById('progressBar');
const progressText = document.getElementById('progressText');

let confirmClicked = false;

amountInput.addEventListener('input', () => {
  const amt = parseFloat(amountInput.value);
  const fee = (amt * 0.02).toFixed(2);
  const net = (amt - fee).toFixed(2);
  if (amt > 0) {
    feeOutput.textContent = `Fee (2%): $${fee} | Net: $${net}`;
  } else {
    feeOutput.textContent = '';
  }
});

form.addEventListener('submit', e => {
  e.preventDefault();
  modal.style.display = 'flex';
});

confirmYes.addEventListener('click', () => {
  if (confirmClicked) return;
  confirmClicked = true;

  modal.style.display = 'none';
  loaderScreen.style.display = 'flex';

  const formData = new FormData(form);

  fetch('../submit_withdrawal.php', {
    method: 'POST',
    body: formData
  }).then(res => res.text())
    .then(data => {
      simulateProgress();
    });
});

confirmNo.addEventListener('click', () => {
  modal.style.display = 'none';
});

function simulateProgress() {
  let percent = 0;
  const steps = [7, 15, 19, 45, 60, 75, 78, 81, 94, 100];
  let stepIndex = 0;

  const interval = setInterval(() => {
    if (stepIndex >= steps.length) {
      clearInterval(interval);
      window.location.href = "recieving_entity.php"; // ✅ Change to your page
      return;
    }
    percent = steps[stepIndex];
    progressBar.style.width = percent + "%";
    progressText.textContent = `Compiling Credentials... ${percent}%`;
    stepIndex++;
  }, 1200);
}