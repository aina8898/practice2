$.ajaxSetup({
    header:{
            'X-CSRF-TOKEN':'{{csrf_token()}}'
        }
});

$(function(){
    var companyid = " ";
    function keyword(event){
        companyid = event;
    }

    $('keyword').on('change', function(){
        var id = $(this).val();
        keyword(this);

        $.ajax({
            type:"GET",
            url: "{route('productlist')}}",
            dataType: 'json'
        }).done(function(json){
            console.log(json);
        }).fail(function(){
            alert('event');
        });
        
    });
});

$(function(){
    $('.submitbtn').click(function(event){
        var name = $('#name').val();
        var company = $('#company').val();
        var minPrice = $('#minPrice').val();
        var maxPrice = $('#maxPrice').val();
        var minStock = $('#minStock').val();
        var maxStock = $('#maxStock').val();

        console.log(name);
        console.log(company);
        console.log(minPrice);
        console.log(maxPrice);
        console.log(minStock);
        console.log(maxStock);

        $.ajax({
            header:{
                'X-XSRF-TOKEN': $('meta[name = "csrf-token"]').attr('content')
            },

            type: "get",
            url: "search/",
            datatype: "json",
            data:{
                keyword:name,
                companyId:company,
                minPrice:minPrice,
                maxPrice:maxPrice,
                minStock:minStock,
                maxStock:maxStock,
            }
        })
        .done(function(data){
            console.log('成功');
            console.log(data.Price);
            consoke.log(data,Stock);
            console.log(Array.isArray(data.products));
            var $result = $('#search-result');
            $result.empty();
            $.each(data.products,function(index, products){
                console.log(data.products);
                var imagePath= homeUrl+'/'+data.products[index].image_path.substr(6);
                console.log(imagePath);

                var html= `
                            <tr>
                                <td>${products.id}</td>
                                <td><img src = "${imagePath}" class = "imgsize"></td>
                                <td>${products.name}</td>
                                <td>${products.price}</td>
                                <td>${products.stock}</td>
                                <td>${data.companies[products.company_id -1].name}</td>
                                <td><a href = "{{route('show', ${product_id})}}" method = "POST"}><button class = "edit-btn">詳細表示</button></a></td>
                                <td>
                                    <form action ="{{route('destroy', ${product.if})}}" method = "POST">
                                        <input type = "hidden" name = "_method" value = "DELETE">
                                        <button type "submit" class "delete-btn" onclick = 'return confirm("削除しますか？")'>削除</button>
                                    </form>
                                </td>
                            </tr>

                            `;
                $result.append(html);
            });
        })
        .fail(function(data){
            console.log('失敗');
        });

    });
});

$(function(){
    $('.delete-btn').on('click', function(){
        var deleteConfirm = confirm('削除しますか？');

        if(deleteConfirm == true){
            var clickEle = $(this);
            var userId = clickEle.attr('id');

            $.ajax({
                url: "{{route('productlist')}}",
                type: 'POST',
                data: {'id': userId,'_method': 'DELETE'}
            })

            .done(function(){
                clickEle.parents('tr').remove();
            })

            .fail(function(){
                alert('削除できませんでした');
            });
        }else{
            (function(e){
                e.preventDefault()
            });
        };
    });
});