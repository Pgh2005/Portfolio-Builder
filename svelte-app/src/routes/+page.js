// src/routes/+page.js
export async function load() {
    const res = await fetch('http://localhost/portfolio-builder/wp-json/wp/v2/projects');
    const projects = await res.json();
    return { projects };
  }