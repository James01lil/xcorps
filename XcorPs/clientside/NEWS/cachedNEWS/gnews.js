document.addEventListener("DOMContentLoaded", fetchGNews);

async function fetchGNews() {
  const apiUrl = '../NEWS/cachedNEWS/gnews_proxy.php'; // relative path — adjust if gnews_proxy.php lives elsewhere

  try {
    const response = await fetch(apiUrl);
    const data = await response.json();

    const container = document.getElementById('news-container');

    if (!data.articles || data.articles.length === 0) {
      container.innerHTML = "<p style='color:gray;'>No news articles available.</p>";
      return;
    }

    container.innerHTML = ""; // Clear previous content

    data.articles.forEach(article => {
      const newsCard = document.createElement("div");
      newsCard.style.background = "#111";
      newsCard.style.color = "#fff";
      newsCard.style.borderRadius = "10px";
      newsCard.style.padding = "15px";
      newsCard.style.margin = "10px";
      newsCard.style.boxShadow = "0 4px 8px rgba(0,0,0,0.3)";

      newsCard.innerHTML = `
        <img src="${article.image || ''}" alt="news image" style="width:100%; border-radius:8px; margin-bottom:10px;" />
        <h4 style="color:peru;">${article.title}</h4>
        <p>${article.description || ''}</p>
        <a href="${article.url}" target="_blank" style="color:skyblue;">Read more</a>
      `;

      container.appendChild(newsCard);
    });
  } catch (error) {
    console.error("News fetch error:", error);
    document.getElementById('news-container').innerHTML = "<p style='color:red;'>Failed to fetch news. Please try again later.</p>";
  }
}
