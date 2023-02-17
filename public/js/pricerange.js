
// price range


const elements = document.querySelectorAll(['range-slider']);

elements.forEach(element => {
  element.insertAdjacentHTML('afterend', `
    <output>${element.value}</output>
  `);
});

document.addEventListener('input', e => {
  const input = e.target;
  const output = input.nextElementSibling;
  if (output) {
    output.textContent = input.value;
  }
});

// end price range




document.addEventListener("DOMContentLoaded", function() {
    var demo1 = new Moovie({
      selector: "#example",
      dimensions: {
           width: "100%"
      },
      config: {
          storage: {
             captionOffset: false,
             playrateSpeed: false,
             captionSize: false
          }
      }
    });
});
