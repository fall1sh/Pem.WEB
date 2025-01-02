// Mendapatkan elemen untuk menampilkan item keranjang dan total harga
const keranjangItems = document.getElementById('keranjang-items');
const totalPriceElement = document.getElementById('total-price');
const apiUrl = 'http://localhost/keranjang_api.php';

// Fungsi untuk menampilkan keranjang
async function tampilkanKeranjang() {
  keranjangItems.innerHTML = ''; // Hapus sebelumnya
  let totalPrice = 0;

  try {
    const response = await fetch(apiUrl);
    const keranjang = await response.json();

    // Menampilkan setiap item keranjang
    keranjang.forEach(item => {
      const itemElement = document.createElement('div');
      itemElement.classList.add('keranjang-item');
      itemElement.innerHTML = `
        <p>${item.nama} - Rp ${item.harga.toLocaleString()}</p>
        <p>Harga Total: Rp ${(item.harga * item.jumlah).toLocaleString()}</p>
        <div class="jumlah">
          <button class="btn" onclick="kurangJumlah('${item.id}', ${item.jumlah})">-</button>
          <span class="jumlah-item">${item.jumlah}</span>
          <button class="btn" onclick="tambahJumlah('${item.id}')">+</button>
        </div>
        <button onclick="hapusDariKeranjang('${item.id}')">Hapus</button>
      `;
      keranjangItems.appendChild(itemElement);

      totalPrice += item.harga * item.jumlah; // Menghitung total harga
    });

    totalPriceElement.textContent = totalPrice.toLocaleString(); // Update total harga
  } catch (error) {
    console.error('Error fetching keranjang:', error);
  }
}

// Fungsi untuk menambahkan item ke keranjang
async function addToKeranjang(nama, harga) {
  const newItem = { nama, harga, jumlah: 1 };

  try {
    const response = await fetch(apiUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(newItem),
    });
    const result = await response.json();
    if (result.status === 'success') {
      tampilkanKeranjang(); // Update keranjang
    } else {
      alert('Gagal menambahkan item.');
    }
  } catch (error) {
    console.error('Error adding to keranjang:', error);
  }
}

// Fungsi untuk menambah jumlah produk di keranjang
async function tambahJumlah(id) {
  try {
    const response = await fetch(apiUrl, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id, jumlah: 1 }),
    });
    const result = await response.json();
    if (result.status === 'success') {
      tampilkanKeranjang();
    } else {
      alert('Gagal menambah jumlah.');
    }
  } catch (error) {
    console.error('Error updating jumlah:', error);
  }
}

// Fungsi untuk mengurangi jumlah produk di keranjang
async function kurangJumlah(id, jumlahSekarang) {
  if (jumlahSekarang > 1) {
    try {
      const response = await fetch(apiUrl, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, jumlah: -1 }),
      });
      const result = await response.json();
      if (result.status === 'success') {
        tampilkanKeranjang();
      } else {
        alert('Gagal mengurangi jumlah.');
      }
    } catch (error) {
      console.error('Error updating jumlah:', error);
    }
  } else {
    hapusDariKeranjang(id);
  }
}

// Fungsi untuk menghapus item dari keranjang
async function hapusDariKeranjang(id) {
  try {
    const response = await fetch(apiUrl, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id }),
    });
    const result = await response.json();
    if (result.status === 'success') {
      tampilkanKeranjang();
    } else {
      alert('Gagal menghapus item.');
    }
  } catch (error) {
    console.error('Error deleting from keranjang:', error);
  }
}

// Menampilkan keranjang saat halaman dimuat
tampilkanKeranjang();
