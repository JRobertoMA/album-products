checkSession();
html_categories = "";
contador = 2;

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function add_category() {
    var formElement = document.getElementById("form-category");
    var formdata = new FormData(formElement);
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/category/add_category.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                document.getElementById("form-category").reset();
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function add_collection() {
    var formElement = document.getElementById("form-collection");
    var formdata = new FormData(formElement);
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/collection/add_collection.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                document.getElementById("form-collection").reset();
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function add_model() {
    var formElement = document.getElementById("form-model");
    var formdata = new FormData(formElement);
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/model/add_model.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                document.getElementById("form-model").reset();
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function add_group() {
    var formElement = document.getElementById("form-group");
    var formdata = new FormData(formElement);
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/group/add_group.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                for (const key in response.data.products) {
                    $(`#id-product-${response.data.products[key].id_product_form}`).val(response.data.products[key].id_product);
                }
                $("#btn-save-group").attr("onclick", "update_group();");
                $("#upload_widget_opener").prop("disabled", false);
                $(`#id_group`).val(response.data.id_group);
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function update_group() {
    var formElement = document.getElementById("form-group");
    var formdata = new FormData(formElement);
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/group/update_group.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                for (const key in response.data.products) {
                    $(`#id-product-${response.data.products[key].id_product_form}`).val(response.data.products[key].id_product);
                }
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function add_photo(asset_id, original_filename, url) {
    var id_group = $(`#id_group`).val();
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    formdata.append("id_group", id_group);
    formdata.append("asset_id", asset_id);
    formdata.append("original_filename", original_filename);
    formdata.append("url", url);
    axios.post("modules/photo/add_photo.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                console.log(response.data.answer);
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function categories() {
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/category/categories.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                html = ``;
                for (const key in response.data.results) {
                    html += `<option value="${response.data.results[key].id}">${response.data.results[key].name}</option>`;
                }
                $("#category-product-1").html(html);
                html_categories = html;
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function models() {
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/model/models.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                html = ``;
                for (const key in response.data.results) {
                    html += `<option value="${response.data.results[key].id}">${response.data.results[key].name}</option>`;
                }
                $("#model").html(html);
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function collections() {
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/collection/collections.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                html = ``;
                for (const key in response.data.results) {
                    html += `<option value="${response.data.results[key].id}">${response.data.results[key].name}</option>`;
                }
                $("#collection").html(html);
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function add_product() {
    var html = `
    <div id="product-${contador}" class="mb-1">
        <input type="hidden" name="id_product[]" id="id-product-${contador}" value="id-${contador}">
        <div class="mb-3">
            <label for="name-product-${contador}" class="form-label">Nombre</label>
            <input type="text" name="name_product[]" id="name-product-${contador}" class="form-control">
        </div>
        <div class="row">
            <div class="col-6">
                <label for="code-product-${contador}" class="form-label">Codigo</label>
                <input type="text" name="code_product[]" id="code-product-${contador}" class="form-control">
            </div>
            <div class="col-6">
                <label for="category-product-${contador}" class="form-label">Categoría</label>
                <select name="category_product[]" id="category-product-${contador}" class="form-control">
                    ${html_categories}
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm mt-1" onclick="remove_product(${contador});">Quitar</button>
    </div>`;
    contador++;
    combinationHtml = document.getElementById("products");
    totalCombination = combinationHtml.childElementCount;
    if (totalCombination == 0) {
        document.getElementById("products").innerHTML = html;
    } else {
        ultimateCombination = combinationHtml.lastElementChild;
        ultimateID = ultimateCombination.id;
        $(html).insertAfter(`#${ultimateID}`);
    }
}

function remove_product(id) {
    var id_product = $(`#id-product-${id}`).val();
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    formdata.append("id_product", id_product);
    axios.post("modules/product/remove_product.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        $(`#product-${id}`).remove();
    },(error) => {
        console.log(error);
    });
}

function index() {
    var limit = getParameterByName("limit");
    var formElement = document.getElementById("form-search");
    var formdata = new FormData(formElement);
    formdata.append("jwt", localStorage.getItem("jwt"));
    formdata.append("limit", limit);
    axios.post("modules/index/index.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                localStorage.setItem('search', response.data.search);
                var html = "";
                var name_product = "";
                var barcode_product = "";
                //console.log(response.data.results);
                var results = response.data.results;
                const keysSorted = Object.keys(results).sort(function(a,b){return b-a});
                for (let key = 0; key < keysSorted.length; key++) {
                    html += `
                    <div class="col-md-4 col-6">
					<div class="card shadow-sm">
						<img src="${results[keysSorted[key]].url}" alt="" class="card-img-top img-fluid" onclick="view(${results[keysSorted[key]].id_group});">
						<div class="card-body">
							<p class="card-text text-center" style="font-size: .875rem;"><a href="view.html?group=${results[keysSorted[key]].id_group}" target="_blank">Abrir en nueva pestaña</a></p>
							<div class="d-flex justify-content-between align-items-center"></div>
						</div>
					</div>
				</div>`;
                }
                var before = "";
                var after = "";
                if (response.data.before == "off") {
                    before = `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a></li>`;
                } else {
                    before = `<li class="page-item"><a class="page-link" href="search.html?limit=${response.data.before_count}">Anterior</a></li>`;
                }
                if (response.data.after == "off") {
                    after = `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Siguiente</a></li>`;
                } else {
                    after = `<li class="page-item"><a class="page-link" href="search.html?limit=${response.data.after_count}" aria-disabled="true">Siguiente</a></li>`;
                }
                html += `
                <div class="col-12">
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    ${before}
                    ${after}
                </ul>
                </nav>
               </div> `;
                $("#results-search").html(html);
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function load_index() {
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    axios.post("modules/index/load_index.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                html = `<option value="">Todas</option>`;
                for (const key in response.data.collection) {
                    html += `<option value="${response.data.collection[key].id}">${response.data.collection[key].name}</option>`;
                }
                $("#collection").html(html);
                html = `<option value="">Todas</option>`;
                for (const key in response.data.model) {
                    html += `<option value="${response.data.model[key].id}">${response.data.model[key].name}</option>`;
                }
                $("#model").html(html);
                html = `<option value="">Todas</option>`;
                for (const key in response.data.category) {
                    html += `<option value="${response.data.category[key].id}">${response.data.category[key].name}</option>`;
                }
                $("#category").html(html);
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function search() {
    var limit = getParameterByName("limit");
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    formdata.append("search", localStorage.getItem("search"));
    formdata.append("limit", limit);
    axios.post("modules/group/search_group.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                var html = "";
                var name_product = "";
                var barcode_product = "";
                var results = response.data.results;
                const keysSorted = Object.keys(results).sort(function(a,b){return b-a});
                for (let key = 0; key < keysSorted.length; key++) {
                    html += `
                    <div class="col-md-4 col-6">
					<div class="card shadow-sm">
						<img src="${results[keysSorted[key]].url}" alt="" class="card-img-top img-fluid" onclick="view(${results[keysSorted[key]].id_group});">
						<div class="card-body">
							<p class="card-text text-center" style="font-size: .875rem;"><a href="view.html?group=${results[keysSorted[key]].id_group}" target="_blank">Abrir en nueva pestaña</a></p>
							<div class="d-flex justify-content-between align-items-center"></div>
						</div>
					</div>
				</div>`;
                }
                var before = "";
                var after = "";
                if (response.data.before == "off") {
                    before = `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a></li>`;
                } else {
                    before = `<li class="page-item"><a class="page-link" href="search.html?limit=${response.data.before_count}">Anterior</a></li>`;
                }
                if (response.data.after == "off") {
                    after = `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Siguiente</a></li>`;
                } else {
                    after = `<li class="page-item"><a class="page-link" href="search.html?limit=${response.data.after_count}" aria-disabled="true">Siguiente</a></li>`;
                }
                html += `
                <div class="col-12">
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    ${before}
                    ${after}
                </ul>
                </nav>
               </div> `;
                $("#results-search").html(html);
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function view(id) {
    window.location.href = "view.html?group="+id;
}

function load_view() {
    var group = getParameterByName("group");
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    formdata.append("group", group);
    axios.post("modules/group/group.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        $("#collection").html(response.data.collection);
        $("#model").html(response.data.model);
        html = "";
        for (const key in response.data.photo) {
            html += `<div class="col-md-4 col-6 mb-2">
                        <a data-fancybox="gallery" data-src="${response.data.photo[key].url}" data-caption="${response.data.photo[key].original_filename}">
                            <img src="${response.data.photo[key].url}" class="card-img-top img-thumbnail rounded img-fluid"/>
                        </a>
                    </div>`;
        }
        $("#photos").html(html);
        html = "";
        for (const key in response.data.product) {
            html += `<option value="${response.data.product[key].id_product}">${response.data.product[key].name}</option>`;
        }
        $("#product").html(html);
        html = "";
        for (const key in response.data.note) {
            html += `<figure id="${response.data.note[key].id_note}">
                        <blockquote class="blockquote note-margin">
                            <p>${response.data.note[key].note}</p>
                        </blockquote>
                        <figcaption class="blockquote-footer note-margin">
                            <strong>${response.data.note[key].name} - ${response.data.note[key].barcode}</strong> <cite title="${response.data.note.barcode}">${response.data.note[key].date_add}</cite>
                        </figcaption>
                        <hr class="my-2">
                    </figure>`;
        }
        $("#notes").html(html);
        $("#barcode").val(response.data.product[0].barcode);
    },(error) => {
        console.log(error);
    });
}

function change_product(id) {
    $("#barcode").prop("readonly", true);
    $("#note").prop("readonly", true);
    $("#btn-save-note").prop("disabled", true);
    var formdata = new FormData();
    formdata.append("jwt", localStorage.getItem("jwt"));
    formdata.append("id", id);
    axios.post("modules/product/change_product.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        $("#barcode").val(response.data.barcode);
        $("#barcode").prop("readonly", false);
        $("#note").prop("readonly", false);
        $("#btn-save-note").prop("disabled", false);
    },(error) => {
        console.log(error);
    });
}

function add_note() {
    var group = getParameterByName("group");
    $("#product").prop("readonly", true);
    $("#barcode").prop("readonly", true);
    $("#note").prop("readonly", true);
    $("#btn-save-note").prop("disabled", true);
    var formElement = document.getElementById("form-note");
    var formdata = new FormData(formElement);
    formdata.append("jwt", localStorage.getItem("jwt"));
    formdata.append("group", group);
    axios.post("modules/note/add_note.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                $("#product").prop("readonly", false);
                $("#barcode").prop("readonly", false);
                $("#note").prop("readonly", false);
                $("#note").val("");
                $("#btn-save-note").prop("disabled", false);
                var html = `<figure id="${response.data.note.id_note}">
                                <blockquote class="blockquote note-margin">
                                    <p>${response.data.note.note}</p>
                                </blockquote>
                                <figcaption class="blockquote-footer note-margin">
                                    <strong>${response.data.note.name} - ${response.data.note.barcode}</strong> <cite title="${response.data.note.barcode}">${response.data.note.date_add}</cite>
                                </figcaption>
                                <hr class="my-2">
                            </figure>`;
                combinationHtml = document.getElementById("notes");
                totalCombination = combinationHtml.childElementCount;
                if (totalCombination == 0) {
                    document.getElementById("notes").innerHTML = html;
                } else {
                    ultimateCombination = combinationHtml.lastElementChild;
                    ultimateID = ultimateCombination.id;
                    $(html).insertAfter(`#${ultimateID}`);
                }
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function checkSession() {
    if ( localStorage.getItem("jwt") == undefined) {
        window.location.href = "login.html";
    }
}

function search_group_table() {
    var limit = getParameterByName("limit");
    var formElement = document.getElementById("form-search");
    var formdata = new FormData(formElement);
    formdata.append("jwt", localStorage.getItem("jwt"));
    formdata.append("limit", limit);
    axios.post("modules/group/search_group_table.php", formdata, {headers: { "Content-Type": "multipart/form-data" },}).then((response) => {
        switch (response.data.status) {
            case "ok":
                var html = "";
                var name_product = "";
                var barcode_product = "";
                //console.log(response.data.results);
                var results = response.data.results;
                const keysSorted = Object.keys(results).sort(function(a,b){return b-a});
                for (let key = 0; key < keysSorted.length; key++) {
                    html += `
                    <tr>
                        <th scope="row">
                            <a data-fancybox="gallery" data-src="${results[keysSorted[key]].url}" data-caption="${results[keysSorted[key]].original_filename}">
                                <img src="${results[keysSorted[key]].url}" class="card-img-top img-thumbnail rounded img-fluid" style="max-width: 64px; min-height: 64px;">
                            </a>
                        </th>
                        <td>${results[keysSorted[key]].collection}</td>
                        <td>${results[keysSorted[key]].model}</td>
                        <td>${results[keysSorted[key]].products}</td>
                        <td>
                            <button class="btn btn-primary" type="button"><i class="fas fa-pencil-alt"></i></button>
                            <button class="btn btn-danger" type="button"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;

                }
                var before = "";
                var after = "";
                if (response.data.before == "off") {
                    before = `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Anterior</a></li>`;
                } else {
                    before = `<li class="page-item"><a class="page-link" href="search.html?limit=${response.data.before_count}">Anterior</a></li>`;
                }
                if (response.data.after == "off") {
                    after = `<li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Siguiente</a></li>`;
                } else {
                    after = `<li class="page-item"><a class="page-link" href="search.html?limit=${response.data.after_count}" aria-disabled="true">Siguiente</a></li>`;
                }
                html += `
                <div class="col-12">
                <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    ${before}
                    ${after}
                </ul>
                </nav>
               </div> `;
                $("#results-search").html(html);
                break;
            case "error":
                alert(response.data.answer);
                break;
        }
    },(error) => {
        console.log(error);
    });
}

function change_image_order() {
    
}