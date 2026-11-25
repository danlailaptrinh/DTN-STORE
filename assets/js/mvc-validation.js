document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("form[data-validate]").forEach((form) => {
    const error = document.createElement("div");
    error.className = "alert alert-danger d-none mt-3";
    error.setAttribute("role", "alert");
    form.prepend(error);
    const show = (message) => {
      error.textContent = message;
      error.classList.remove("d-none");
      error.scrollIntoView({ block: "center" });
    };
    form.addEventListener("submit", (event) => {
      error.classList.add("d-none");
      const get = (name) => form.elements.namedItem(name)?.value?.trim() ?? "";
      const product = form.dataset.validate.startsWith("product");
      const variant =
        form.dataset.validate === "variant" ||
        form.dataset.validate === "product-create";
      const prefix = form.dataset.validate === "product-create" ? "init_" : "";
      let message = "";
      if (product) {
        if (!get("product_name") || !get("category_id")) {
          message = "Vui lòng nhập tên sản phẩm và danh mục.";
        } else if (form.elements.namedItem("brand") && !get("brand")) {
          message = "Vui lòng nhập thương hiệu.";
        }
      }
      if (variant && !message) {
        const stockName = prefix ? "init_stock" : "stock_quantity";
        const rules = [
          [prefix + "ram", 0],
          [prefix + "storage", 1],
          [stockName, 0],
        ];
        for (const [name, min] of rules) {
          const value = get(name);
          if (
            !/^\d+$/.test(value) ||
            Number(value) < min ||
            Number(value) > 1000000
          ) {
            message = "RAM, dung lượng và tồn kho phải là số nguyên hợp lệ.";
            break;
          }
        }
        if (
          !message &&
          (!get(prefix + "color") ||
            !/^[A-Za-z0-9][A-Za-z0-9._-]*$/.test(get(prefix + "sku")))
        )
          message =
            "Hãy nhập màu sắc và mã SKU hợp lệ (chữ không dấu, số, . _ -).";
        const price = get(prefix + "sale_price");
        if (
          !message &&
          (!/^\d{1,13}(?:\.\d{1,2})?$/.test(price) ||
            Number(price) > 9999999999999.99)
        )
          message = "Giá bán không hợp lệ.";
      }
      const images = form.querySelector('input[name="images[]"]');
      if (!message && images?.files) {
        if (images.files.length > 10) message = "Chỉ chọn tối đa 10 ảnh.";
        for (const file of images.files) {
          if (
            !["image/jpeg", "image/png", "image/webp"].includes(file.type) ||
            file.size > 5 * 1024 * 1024
          ) {
            message = "Ảnh chỉ được là JPG/PNG/WEBP, tối đa 5MB mỗi ảnh.";
            break;
          }
        }
      }
      if (message) {
        event.preventDefault();
        show(message);
      }
    });
  });
});
