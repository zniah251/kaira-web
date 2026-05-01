const allProducts = [
    // ==== SẢN PHẨM NAM - ÁO POLO & ÁO THUN NAM (shirts.html) ====
    {
        id: "nam-polo-thun-01",
        name: "Áo polo cổ bé tay ngắn in hoạ tiết chữ hiện đại",
        mainImage: "../../../admin/assets/images/ao-polo-co-be-tay-ngan-in-hoa-tiet-chU-hien-ai.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-polo-co-be-tay-ngan-in-hoa-tiet-chU-hien-ai-2.webp",
            "../../../admin/assets/images/ao-polo-co-be-tay-ngan-in-hoa-tiet-chU-hien-ai-3.webp", 
            "../../../admin/assets/images/ao-polo-co-be-tay-ngan-in-hoa-tiet-chU-hien-ai-4.webp" 
        ],
        priceOld: 199000, priceNew: 159000, rating: 4, reviewCount: 18,
        description: "Chiếc áo polo thời thượng này là sự kết hợp hoàn hảo giữa vẻ cổ điển và nét hiện đại. Thiết kế cổ bẻ tay ngắn cùng họa tiết chữ độc đáo tạo điểm nhấn ấn tượng, phù hợp cho những ai yêu thích phong cách trẻ trung và cá tính. Chất liệu vải mềm mại, thấm hút tốt, mang lại sự thoải mái tối đa trong mọi hoạt động hàng ngày.",
        material: "Cotton cao cấp", form: "Regular fit",
        colors: [{ name: "Đen", hex: "#000000" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-polo-thun-02",
        name: "Áo polo nam cổ bé tay ngắn trẻ trung",
        mainImage: "../../../admin/assets/images/ao-polo-nam-co-be-tay-ngan-tre-trung.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-polo-nam-co-be-tay-ngan-tre-trung-2.webp",
            "../../../admin/assets/images/ao-polo-nam-co-be-tay-ngan-tre-trung-3.webp",
            "../../../admin/assets/images/ao-polo-nam-co-be-tay-ngan-tre-trung-4.webp"


        ],
        priceOld: 160000, priceNew: 140000, rating: 5, reviewCount: 25,
        description: "Mẫu áo polo này mang đến sự thoải mái tối đa với chất liệu thoáng mát, lý tưởng cho những ngày năng động. Thiết kế đơn giản, dễ phối đồ nhưng vẫn giữ được nét thanh lịch và trẻ trung cho người mặc. Áo có khả năng co giãn tốt, giúp bạn tự tin vận động.",
        material: "Cotton pha", form: "Fitted",
        colors: [{ name: "Cam", hex: "#FF6600" }],
        sizes: ["M", "L", "XL"]
    },
    {
        id: "nam-polo-thun-03",
        name: "Áo polo ngắn tay nam S.cafe phối cổ.fitted",
        mainImage: "../../../admin/assets/images/ao-polo-ngan-tay-nam-S.Cafe-phoi-co.Fitted.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-polo-ngan-tay-nam-S.Cafe-phoi-co.Fitted-2.webp",
            "../../../admin/assets/images/ao-polo-ngan-tay-nam-S.Cafe-phoi-co.Fitted-3.webp",
            "../../../admin/assets/images/ao-polo-ngan-tay-nam-S.Cafe-phoi-co.Fitted-4.webp",
            "../../../admin/assets/images/ao-polo-ngan-tay-nam-S.Cafe-phoi-co.Fitted-5.webp",
            "../../../admin/assets/images/ao-polo-ngan-tay-nam-S.Cafe-phoi-co.Fitted-6.webp"

        ],
        priceOld: 299000, priceNew: 269000, rating: 3, reviewCount: 10,
        description: "S.cafe là công nghệ vải tiên tiến, giúp áo khô nhanh và kiểm soát mùi hiệu quả. Chiếc áo polo này không chỉ có form dáng ôm vừa vặn tôn dáng mà còn mang lại cảm giác cực kỳ dễ chịu suốt cả ngày dài. Hoàn hảo cho các hoạt động thường ngày hoặc dạo phố, thể hiện sự đẳng cấp của bạn.",
        material: "Vải S.Cafe", form: "Fitted",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nam-polo-thun-04",
        name: "Áo polo cổ bé tay ngắn thời thượng",
        mainImage: "../../../admin/assets/images/ao-polo-co-be-tay-ngan-thoi-thuong-2.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-polo-co-be-tay-ngan-thoi-thuong-2.webp",
            "../../../admin/assets/images/ao-polo-co-be-tay-ngan-thoi-thuong-3.webp",
            "../../../admin/assets/images/ao-polo-co-be-tay-ngan-thoi-thuong-4.webp"
        ],
        priceOld: 249000, priceNew: 239000, rating: 4, reviewCount: 15,
        description: "Mang hơi thở của sự thanh lịch và phóng khoáng, chiếc áo polo này là lựa chọn lý tưởng cho các buổi hẹn hò hay dã ngoại cuối tuần. Chất liệu mềm mại và đường may tinh tế đảm bảo sự thoải mái tối đa và bền đẹp theo thời gian, giúp bạn luôn tự tin và phong độ.",
        material: "Vải Modal", form: "Regular fit",
        colors: [{ name: "Trắng", hex: "#ffffff" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-polo-thun-05",
        name: "Áo polo nam ngắn tay cotton",
        mainImage: "../../../admin/assets/images/ao-polo-nam-ngan-tay-cotton.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-polo-nam-ngan-tay-cotton.webp",
            "../../../admin/assets/images/ao-polo-nam-ngan-tay-cotton-2.webp",
            "../../../admin/assets/images/ao-polo-nam-ngan-tay-cotton-3.webp"
        ],
        priceOld: 199000, priceNew: 159000, rating: 5, reviewCount: 30,
        description: "Một item cơ bản nhưng không bao giờ lỗi mốt. Áo polo cotton ngắn tay này dễ dàng kết hợp với nhiều loại trang phục, từ quần jeans cá tính đến quần âu lịch lãm. Vải cotton tự nhiên mang lại sự mềm mại và khả năng thấm hút mồ hôi vượt trội, lý tưởng cho những ngày hè nóng bức.",
        material: "100% Cotton", form: "Classic fit",
        colors: [{ name: "Đen", hex: "#000000" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-polo-thun-06",
        name: "Áo polo nam premium 100% cotton phối sọc",
        mainImage: "../../../admin/assets/images/ao-polo-nam-premium-100_-cotton-phoi-soc-form-fitt.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-polo-nam-premium-100_-cotton-phoi-soc-form-fitt.webp",
            "../../../admin/assets/images/ao-polo-nam-premium-100_-cotton-phoi-soc-form-fitt-2.webp",
            "../../../admin/assets/images/ao-polo-nam-premium-100_-cotton-phoi-soc-form-fitt-3.webp",
            "../../../admin/assets/images/ao-polo-nam-premium-100_-cotton-phoi-soc-form-fitt-4.webp",
            "../../../admin/assets/images/ao-polo-nam-premium-100_-cotton-phoi-soc-form-fitt-5.webp"
        ],
        priceOld: 310000, priceNew: 300000, rating: 5, reviewCount: 22,
        description: "Sự cao cấp toát lên từ từng đường sọc tinh tế và chất liệu 100% cotton thượng hạng. Áo mang form dáng vừa vặn, tạo nên vẻ ngoài thanh lịch và hiện đại, lý tưởng cho môi trường công sở hoặc những buổi gặp gỡ quan trọng. Sản phẩm này cam kết độ bền và sự thoải mái dài lâu.",
        material: "100% Cotton Premium", form: "Fitted",
        colors: [{ name: "Trắng sọc xám", hex: "FFFFFF" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-polo-thun-07",
        name: "Áo polo nam thời trang basic dễ phối đồ",
        mainImage: "../../../admin/assets/images/ao-polo-nam-thoi-trang-basic-de-phoi-o.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/ao-polo-nam-thoi-trang-basic-de-phoi-o.jpg",
            "../../../admin/assets/images/ao-polo-nam-thoi-trang-basic-de-phoi-o-3.jpg"

        ],
        priceOld: 149000, priceNew: 129000, rating: 4, reviewCount: 18,
        description: "Chiếc áo polo basic này là nền tảng cho mọi tủ đồ nam. Dễ dàng phối với quần jean, quần khaki hay quần shorts, mang lại phong cách năng động và lịch sự. Chất vải mềm, không nhăn, giữ form tốt, thích hợp cho việc mặc hàng ngày và đi chơi.",
        material: "Cotton tổng hợp", form: "Regular fit",
        colors: [{ name: "Nâu", hex: "#654321" }],
        sizes: ["S", "M", "L", "XLXL"]
    },
    {
        id: "nam-polo-thun-08",
        name: "Áo polo nam trơn vải cotton polyester",
        mainImage: "../../../admin/assets/images/ao-polo-nam-tron-vai-cotton-polyester.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-polo-nam-tron-vai-cotton-polyester.webp",
            "../../../admin/assets/images/ao-polo-nam-tron-vai-cotton-polyester-2.webp",
            "../../../admin/assets/images/ao-polo-nam-tron-vai-cotton-polyester-3.webp",
            "../../../admin/assets/images/ao-polo-nam-tron-vai-cotton-polyester-4.webp",
            "../../../admin/assets/images/ao-polo-nam-tron-vai-cotton-polyester-5.webp",
            "../../../admin/assets/images/ao-polo-nam-tron-vai-cotton-polyester-6.webp"
        ],
        priceOld: 299000, priceNew: 269000, rating: 3, reviewCount: 8,
        description: "Với chất liệu cotton polyester, áo polo này mang lại sự thoáng khí và bền bỉ. Bề mặt vải trơn, mịn tạo cảm giác sang trọng. Thích hợp cho cả trang phục hàng ngày và các sự kiện bán trang trọng, giữ cho bạn vẻ ngoài lịch sự và gọn gàng.",
        material: "Cotton Polyester", form: "Classic fit",
        colors: [{ name: "Be", hex: "#F5F5DC" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-polo-thun-09",
        name: "Áo thun ngắn tay nam",
        mainImage: "../../../admin/assets/images/ao-Thun-Ngan-Tay-Nam.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-Thun-Ngan-Tay-Nam.webp",
            "../../../admin/assets/images/ao-Thun-Ngan-Tay-Nam-2.webp",
            "../../../admin/assets/images/ao-Thun-Ngan-Tay-Nam3.webp",
            "../../../admin/assets/images/ao-Thun-Ngan-Tay-Nam4.webp",
            "../../../admin/assets/images/ao-Thun-Ngan-Tay-Nam5.webp"
        ],
        priceOld: 189000, priceNew: 159000, rating: 5, reviewCount: 40,
        description: "Chiếc áo thun cơ bản với chất liệu cotton mềm mại, thoáng mát, là lựa chọn lý tưởng cho các hoạt động hàng ngày. Kiểu dáng trẻ trung, thoải mái, dễ dàng kết hợp với nhiều phong cách khác nhau, từ thể thao đến dạo phố.",
        material: "100% Cotton", form: "Loose fit",
        colors: [{ name: "Xanh dương", hex: "#3EAEF4" }],
        sizes: ["S", "M", "L", "XL"]
    },

    // ==== SẢN PHẨM NAM - ÁO SƠ MI NAM (t-shirts.html) ====
    {
        id: "nam-somi-01",
        name: "Áo Sơ Mi Cuban Nam Họa Tiết Marvel Comic",
        mainImage: "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Hoa-Tiet-Marvel-Comic.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Hoa-Tiet-Marvel-Comic.webp",
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Hoa-Tiet-Marvel-Comic2.webp",
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Hoa-Tiet-Marvel-Comic3.webp",
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Hoa-Tiet-Marvel-Comic4.webp",
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Hoa-Tiet-Marvel-Comic5.webp"
            
        ],
        priceOld: 150000, priceNew: 120000, rating: 5, reviewCount: 20,
        description: "Áo sơ mi Cuban độc đáo với họa tiết Marvel Comic, dành cho fan hâm mộ và những ai muốn thể hiện cá tính riêng. Chất vải mát mẻ, form dáng rộng rãi, thích hợp cho mùa hè, giúp bạn tự tin và nổi bật giữa đám đông.",
        material: "Lụa Latin", form: "Relax fit",
        colors: [{ name: "Đen", hex: "#000000" }, { name: "Xám", hex: "#808080" }],
        sizes: ["S", "M", "L", "XLXL"]
    },
    {
        id: "nam-somi-02",
        name: "Áo Sơ Mi Cuban Nam Tay",
        mainImage: "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Tay.webp",
        thumbnailImages: [
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Tay.webp",
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Tay2.webp",
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Tay3.webp",
            "../../../admin/assets/images/ao-So-Mi-Cuban-Nam-Tay4.webp"
        ],
        priceOld: 210000, priceNew: 180000, rating: 4, reviewCount: 15,
        description: "Thiết kế tay ngắn kết hợp phong cách Cuban cổ điển, mang đến sự phóng khoáng và lịch thiệp. Áo được làm từ vải thoáng khí, rất phù hợp với khí hậu nhiệt đới, lý tưởng cho các buổi dạo chơi, đi biển hoặc cafe cùng bạn bè.",
        material: "Cotton linen", form: "Regular fit",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-somi-03",
        name: "Áo sơ mi dài tay nam kẻ caro xanh da trời",
        mainImage: "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-cotton-mau-xanh-da-troi.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-cotton-mau-xanh-da-troi.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-cotton-mau-xanh-da-troi-22.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-cotton-mau-xanh-da-troi-3.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-cotton-mau-xanh-da-troi-4.jpg"

        ],
        priceOld: 195000, priceNew: 165000, rating: 3, reviewCount: 10,
        description: "Áo sơ mi kẻ caro kinh điển, tông màu xanh da trời mang đến vẻ ngoài tươi sáng, dễ chịu. Chất liệu cotton mềm mại, phù hợp mặc đi học, đi làm hoặc đi chơi, đảm bảo sự thoải mái và lịch sự suốt cả ngày.",
        material: "Cotton", form: "Regular fit",
        colors: [{ name: "Xanh da trời", hex: "#87CEEB" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-somi-04",
        name: "Áo sơ mi dài tay nam kẻ caro xanh dương",
        mainImage: "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-mau-xanh-duong.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-mau-xanh-duong.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-mau-xanh-duong-22.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-mau-xanh-duong-3.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-ke-caro-mau-xanh-duong-4.jpg"
        ],
        priceOld: 220000, priceNew: 185000, rating: 4, reviewCount: 13,
        description: "Họa tiết kẻ caro phối màu xanh dương đậm nét, tạo nên sự trẻ trung và hiện đại. Áo sơ mi dài tay này thích hợp cho mọi mùa trong năm, có thể mặc riêng hoặc khoác ngoài áo thun, mang lại phong cách đa năng và cá tính.",
        material: "Cotton blend", form: "Regular fit",
        colors: [{ name: "Xanh dương", hex: "#0000FF" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-somi-05",
        name: "Áo sơ mi dài tay nam màu đen trơn",
        mainImage: "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-en-tron.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-en-tron.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-en-tron-2.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-en-tron-3.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-en-tron-4.jpg"
        ],
        priceOld: 300000, priceNew: 265000, rating: 5, reviewCount: 25,
        description: "Sự thanh lịch và sang trọng của chiếc sơ mi đen trơn là không thể phủ nhận. Dễ dàng kết hợp với quần tây, quần jean, vest... phù hợp cho mọi sự kiện từ công sở đến các bữa tiệc sang trọng, khẳng định phong cách riêng của bạn.",
        material: "Lụa tuyết", form: "Slim fit",
        colors: [{ name: "Đen", hex: "#000000" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-somi-06",
        name: "Áo sơ mi dài tay nam màu xanh",
        mainImage: "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-xanh.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-xanh.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-xanh2.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-xanh-3.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-mau-xanh-4.jpg"

        ],
        priceOld: 250000, priceNew: 220000, rating: 4, reviewCount: 18,
        description: "Màu xanh trầm tinh tế, mang lại vẻ ngoài điềm đạm và cuốn hút. Chất liệu mềm mại, ít nhăn, dễ dàng giặt ủi. Sản phẩm lý tưởng cho những người ưa thích phong cách tối giản mà vẫn sang trọng, tiện lợi cho mọi hoàn cảnh.",
        material: "Cotton modal", form: "Regular fit",
        colors: [{ name: "Xanh dương", hex: "#0000FF" }, { name: "Xanh biển", hex: "#1E90FF" }],
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-somi-07",
        name: "Áo sơ mi dài tay nam tím than",
        mainImage: "../../../admin/assets/images/ao-so-mi-dai-tay-nam-tim-than.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-tim-than.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-tim-than-2.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-tim-than-3.jpg",
            "../../../admin/assets/images/ao-so-mi-dai-tay-nam-tim-than-4.jpg"
        ],
        priceOld: 290000, priceNew: 250000, rating: 4, reviewCount: 14,
        description: "Tông màu tím than độc đáo, tạo nên vẻ ngoài lịch lãm và khác biệt. Chiếc áo sơ mi này rất phù hợp để thể hiện sự tự tin và phong thái chuyên nghiệp trong các cuộc họp hay sự kiện quan trọng, giúp bạn nổi bật theo cách tinh tế.",
        material: "Cotton lụa", form: "Slim fit",
        colors: [{ name: "Tím than", hex: "#483D8B" }],
        sizes: ["M", "L", "XL"]
    },
    {
        id: "nam-somi-08",
        name: "Áo Sơ Mi Linen Nam Tay Ngắn Xanh",
        mainImage: "../../../admin/assets/images/ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh.jpg",
            "../../../admin/assets/images/ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh-2.jpg",
            "../../../admin/assets/images/ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh-3.jpg",
            "../../../admin/assets/images/ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh-4.jpg",
            "../../../admin/assets/images/ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh-5.jpg",
            "../../../admin/assets/images/ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh-6.jpg"
        ],
        priceOld: 190000, priceNew: 150000, rating: 5, reviewCount: 22,
        description: "Chất liệu linen thoáng mát, là lựa chọn tuyệt vời cho những ngày nắng nóng. Màu xanh tươi sáng, kết hợp với thiết kế tay ngắn phóng khoáng, mang lại vẻ ngoài năng động và đầy sức sống, thích hợp cho những buổi dạo chơi ngày hè.",
        material: "Linen", form: "Regular fit",
        colors: [{ name: "Xanh lá cây", hex: "#008000" }],
        sizes: ["S", "M", "L", "XL"]
    },

    // ==== SẢN PHẨM NAM - QUẦN SHORTS NAM (shorts.html) ====
    {
        id: "nam-shorts-01",
        name: "Quần short thể thao nam phối viền polyester",
        mainImage: "../../../admin/assets/images/Quan-short-the-thao-nam-phoi-vien-polyester.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-short-the-thao-nam-phoi-vien-polyester.webp",
            "../../../admin/assets/images/Quan-short-the-thao-nam-phoi-vien-polyester-2.webp",
            "../../../admin/assets/images/Quan-short-the-thao-nam-phoi-vien-polyester-3.webp",
            "../../../admin/assets/images/Quan-short-the-thao-nam-phoi-vien-polyester-4.webp"
        ],
        priceOld: 200000, priceNew: 170000, rating: 4, reviewCount: 12,
        description: "Quần short thể thao thoải mái với chất liệu polyester cao cấp, khô thoáng nhanh. Thiết kế phối viền nổi bật, phù hợp cho các buổi tập gym, chạy bộ hoặc dạo phố năng động, mang lại vẻ ngoài trẻ trung và tiện lợi.",
        material: "Polyester", form: "Regular fit",
        colors: [{ name: "Xám", hex: "#808080" }], 
        sizes: ["M", "L", "XL"]
    },
    {
        id: "nam-shorts-02",
        name: "Quần short denim nam form straight",
        mainImage: "../../../admin/assets/images/Quan-short-denim-nam-form-straight.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-short-denim-nam-form-straight.webp",
            "../../../admin/assets/images/Quan-short-denim-nam-form-straight-2.webp",
            "../../../admin/assets/images/Quan-short-denim-nam-form-straight-3.webp",
            "../../../admin/assets/images/Quan-short-denim-nam-form-straight-4.webp",
            "../../../admin/assets/images/Quan-short-denim-nam-form-straight-5.webp"
        ],
        priceOld: 250000, priceNew: 220000, rating: 5, reviewCount: 18,
        description: "Quần short denim trẻ trung với form straight đứng dáng, mang lại vẻ ngoài bụi bặm nhưng không kém phần lịch sự. Chất liệu denim cao cấp, bền màu và thoải mái khi mặc, lý tưởng cho những ngày cuối tuần.",
        material: "Denim", form: "Straight",
        colors: [{ name: "Xanh jean", hex: "#4169E1" }], sizes: ["M", "L", "XL"]
    },
    {
        id: "nam-shorts-03",
        name: "Quần short nam nỉ gân French Terry form relax",
        mainImage: "../../../admin/assets/images/Quan-short-nam-ni-gan-french-terry-form-relax.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-short-nam-ni-gan-french-terry-form-relax.webp",
            "../../../admin/assets/images/Quan-short-nam-ni-gan-french-terry-form-relax-22.webp",
            "../../../admin/assets/images/Quan-short-nam-ni-gan-french-terry-form-relax-3.webp",
            "../../../admin/assets/images/Quan-short-nam-ni-gan-french-terry-form-relax-4.webp"
        ],
        priceOld: 180000, priceNew: 150000, rating: 3, reviewCount: 7,
        description: "Chiếc quần short nỉ gân với chất liệu French Terry mềm mại, tạo cảm giác thoải mái và ấm áp. Form relax năng động, phù hợp cho những ngày nghỉ ngơi hoặc dạo phố cùng bạn bè, mang lại phong cách effortless cool.",
        material: "Nỉ French Terry", form: "Relax",
        colors: [{ name: "Đen", hex: "#000000" }], sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-shorts-04",
        name: "Quần short nam nylon form relax",
        mainImage: "../../../admin/assets/images/Quan-short-nam-nylon-form-relax.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-short-nam-nylon-form-relax.webp",
            "../../../admin/assets/images/Quan-short-nam-nylon-form-relax-2.webp",
            "../../../admin/assets/images/Quan-short-nam-nylon-form-relax-3.webp",
            "../../../admin/assets/images/Quan-short-nam-nylon-form-relax-4.webp",
            "../../../admin/assets/images/Quan-short-nam-nylon-form-relax-5.webp"
        ],
        priceOld: 160000, priceNew: 135000, rating: 4, reviewCount: 9,
        description: "Quần short nylon nhẹ nhàng, chống nước nhẹ, thích hợp cho những chuyến đi hoặc hoạt động ngoài trời. Form relax thoải mái, mang lại sự linh hoạt trong mọi cử động, và dễ dàng vệ sinh sau khi sử dụng.",
        material: "Nylon", form: "Relax",
        colors: [{ name: "Be", hex: "#F5F5DC" }], sizes: ["M", "L", "XL"]
    },
    {
        id: "nam-shorts-05",
        name: "Quần short nam trơn cotton form straight",
        mainImage: "../../../admin/assets/images/Quan-short-nam-tron-cotton-form-straight.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-short-nam-tron-cotton-form-straight.webp",
            "../../../admin/assets/images/Quan-short-nam-tron-cotton-form-straight-2.webp",
            "../../../admin/assets/images/Quan-short-nam-tron-cotton-form-straight-3.webp",
            "../../../admin/assets/images/Quan-short-nam-tron-cotton-form-straight-4.webp",
            "../../../admin/assets/images/Quan-short-nam-tron-cotton-form-straight-5.webp"
        ],
        priceOld: 230000, priceNew: 195000, rating: 5, reviewCount: 14,
        description: "Quần short trơn màu cơ bản với chất liệu cotton thoáng mát, form straight lịch sự, dễ dàng phối hợp với mọi loại áo. Sản phẩm không thể thiếu trong tủ đồ của quý ông, mang đến sự tinh tế và dễ chịu.",
        material: "Cotton", form: "Straight",
        colors: [{ name: "Be", hex: "#F5F5DC" }], sizes: ["M", "L", "XL"]    
    },
    
    {
        id: "nam-shorts-06",
        name: "Quần short nỉ nam nhấn trang trí form relax",
        mainImage: "../../../admin/assets/images/Quan-short-ni-nam-nhan-trang-tri-form-relax.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-short-ni-nam-nhan-trang-tri-form-relax.webp",
            "../../../admin/assets/images/Quan-short-ni-nam-nhan-trang-tri-form-relax-2.webp",
            "../../../admin/assets/images/Quan-short-ni-nam-nhan-trang-tri-form-relax-3.webp",
            "../../../admin/assets/images/Quan-short-ni-nam-nhan-trang-tri-form-relax-4.webp",
            "../../../admin/assets/images/Quan-short-ni-nam-nhan-trang-tri-form-relax-5.webp"
        ],
        priceOld: 200000, priceNew: 170000, rating: 5, reviewCount: 16,
        description: "Quần short nỉ với các chi tiết nhấn nhá trang trí tinh tế, tạo điểm nhấn cho phong cách. Form relax thoải mái, phù hợp cho những hoạt động thường ngày hoặc khi bạn muốn có một set đồ cá tính và năng động, đảm bảo sự linh hoạt tối đa.",
        material: "Nỉ", form: "Relax",
        colors: [{ name: "Xanh dương", hex: "#27A4F2" }], sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-shorts-07",
        name: "Quần shorts thể thao nam dây kéo sau regular",
        mainImage: "../../../admin/assets/images/Quan-shorts-the-thao-nam-day-keo-sau-regular.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-shorts-the-thao-nam-day-keo-sau-regular.webp",
            "../../../admin/assets/images/Quan-shorts-the-thao-nam-day-keo-sau-regular-2.webp",
            "../../../admin/assets/images/Quan-shorts-the-thao-nam-day-keo-sau-regular-3.webp",
            "../../../admin/assets/images/Quan-shorts-the-thao-nam-day-keo-sau-regular-4.webp"
        ],
        priceOld: 270000, priceNew: 230000, rating: 4, reviewCount: 11,
        description: "Quần shorts thể thao chuyên nghiệp với thiết kế dây kéo sau tiện lợi, giúp bạn giữ đồ an toàn khi vận động. Form regular năng động, tối ưu hóa sự thoải mái trong mọi hoạt động thể thao, là lựa chọn hoàn hảo cho những buổi tập luyện cường độ cao.",
        material: "Polyester co giãn", form: "Regular",
        colors: [{ name: "Xanh dương", hex: "#27A4F2" }], sizes: ["S", "M", "L", "XL"]
    },

    // ==== SẢN PHẨM NAM - QUẦN DÀI NAM (trousers.html) ====
    {
        id: "nam-quandai-01",
        name: "Quần dài nam điểu gân form carrot",
        mainImage: "../../../admin/assets/images/Quan-dai-nam-dieu-gan-form-carrot.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-dai-nam-dieu-gan-form-carrot.webp",
            "../../../admin/assets/images/Quan-dai-nam-dieu-gan-form-carrot-2.webp",
            "../../../admin/assets/images/Quan-dai-nam-dieu-gan-form-carrot-3.webp",
            "../../../admin/assets/images/Quan-dai-nam-dieu-gan-form-carrot-4.webp",
            "../../../admin/assets/images/Quan-dai-nam-dieu-gan-form-carrot-5.webp"
        ],
        priceOld: 260000, priceNew: 230000, rating: 4, reviewCount: 15,
        description: "Quần dài nam với kiểu dáng form carrot hiện đại, ống côn dần xuống mắt cá chân, tạo vẻ ngoài thon gọn. Chất liệu điểu gân độc đáo, co giãn nhẹ, phù hợp cho cả công sở và dạo phố, mang lại sự năng động và thoải mái.",
        material: "Vải điểu gân", form: "Carrot",
        colors: [{ name: "Đen", hex: "#000000" }], sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-quandai-02",
        name: "Quần tây dài nam gấp ống form slim crop",
        mainImage: "../../../admin/assets/images/Quan-tay-dai-nam-gap-ong-form-slimcrop-2.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-tay-dai-nam-gap-ong-form-slimcrop-2.webp",
            "../../../admin/assets/images/Quan-tay-dai-nam-gap-ong-form-slimcrop-3.webp",
            "../../../admin/assets/images/Quan-tay-dai-nam-gap-ong-form-slimcrop-4.webp",
            "../../../admin/assets/images/Quan-tay-dai-nam-gap-ong-form-slimcrop-5.webp",
            "../../../admin/assets/images/Quan-tay-dai-nam-gap-ong-form-slimcrop-6.webp"
        ],
        priceOld: 300000, priceNew: 270000, rating: 5, reviewCount: 20,
        description: "Quần tây nam form slim crop thanh lịch, phần ống gấp mang lại nét cá tính và thời trang. Chất vải co giãn, giữ form tốt, lý tưởng cho những người theo phong cách trẻ trung, hiện đại và luôn muốn đổi mới.",
        material: "Twill", form: "Slim Crop",
        colors: [{ name: "Đen", hex: "#000000" }], sizes: ["S", "M", "L", "XL"]    },
    {
        id: "nam-quandai-03",
        name: "Quần tây nam dài lưng thun",
        mainImage: "../../../admin/assets/images/Quan-tay-nam-dai-lung-thun.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-tay-nam-dai-lung-thun-2.webp",
            "../../../admin/assets/images/Quan-tay-nam-dai-lung-thun-3.webp",
            "../../../admin/assets/images/Quan-tay-nam-dai-lung-thun-4.webp",
            "../../../admin/assets/images/Quan-tay-nam-dai-lung-thun-5.webp",
            "../../../admin/assets/images/Quan-tay-nam-dai-lung-thun-6.webp"

        ],
        priceOld: 210000, priceNew: 180000, rating: 3, reviewCount: 8,
        description: "Thoải mái tối đa với thiết kế lưng thun linh hoạt, quần tây này vẫn giữ được vẻ ngoài lịch sự và gọn gàng. Chất vải mềm mại, ít nhăn, phù hợp cho những ngày dài hoạt động, mang lại sự dễ chịu tuyệt đối.",
        material: "Viscose", form: "Regular",
        colors: [ { name: "Xám", hex: "#808080" }], sizes: ["S", "M", "L", "XLXL"]
    },
    {
        id: "nam-quandai-04",
        name: "Quần vải nam dài phối lót trơn form slim",
        mainImage: "../../../admin/assets/images/Quan-vai-nam-dai-phoi-lot-tron-form-slim.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-vai-nam-dai-phoi-lot-tron-form-slim.webp",
            "../../../admin/assets/images/Quan-vai-nam-dai-phoi-lot-tron-form-slim-22.webp",
            "../../../admin/assets/images/Quan-vai-nam-dai-phoi-lot-tron-form-slim-3.webp",
            "../../../admin/assets/images/Quan-vai-nam-dai-phoi-lot-tron-form-slim-4.webp",
            "../../../admin/assets/images/Quan-vai-nam-dai-phoi-lot-tron-form-slim-5.webp"
        ],
        priceOld: 290000, priceNew: 250000, rating: 4, reviewCount: 10,
        description: "Quần vải dài với lớp lót mềm mại bên trong, tạo cảm giác thoải mái và đứng form. Form slim tôn dáng, phù hợp cho môi trường công sở hoặc các sự kiện cần sự trang trọng, mang đến vẻ ngoài chuyên nghiệp và tự tin.",
        material: "Vải Polyester pha", form: "Slim",
        colors: [{ name: "Trắng", hex: "#ffffff" }], sizes: ["S", "M", "L"]
    },
    {
        id: "nam-quandai-05",
        name: "Quần denim nam ống rộng form loose",
        mainImage: "../../../admin/assets/images/Quan-denim-nam-ong-rong-form-loose.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-denim-nam-ong-rong-form-loose.webp",
            "../../../admin/assets/images/Quan-denim-nam-ong-rong-form-loose-2.webp",
            "../../../admin/assets/images/Quan-denim-nam-ong-rong-form-loose-3.webp",
            "../../../admin/assets/images/Quan-denim-nam-ong-rong-form-loose-4.webp",
            "../../../admin/assets/images/Quan-denim-nam-ong-rong-form-loose-5.webp"
        ],
        priceOld: 270000, priceNew: 235000, rating: 5, reviewCount: 18,
        description: "Xu hướng ống rộng mang lại vẻ ngoài năng động và cá tính. Quần denim này không chỉ thoải mái mà còn cực kỳ thời trang, dễ dàng kết hợp với áo phông, hoodie cho phong cách đường phố đầy ấn tượng.",
        material: "Denim cao cấp", form: "Loose",
        colors: [{ name: "Xanh jeans", hex: "#2243b6" }], sizes: ["S", "M", "L"]
    },
    {
        id: "nam-quandai-06",
        name: "Quần denim nam ống đứng form straight",
        mainImage: "../../../admin/assets/images/Quan-denim-nam-ong-ung-form-straight.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-denim-nam-ong-ung-form-straight.webp",
            "../../../admin/assets/images/Quan-denim-nam-ong-ung-form-straight-22.webp",
            "../../../admin/assets/images/Quan-denim-nam-ong-ung-form-straight-3.webp",
            "../../../admin/assets/images/Quan-denim-nam-ong-ung-form-straight-4.webp",
            "../../../admin/assets/images/Quan-denim-nam-ong-ung-form-straight-5.webp"
            
        ],
        priceOld: 260000, priceNew: 225000, rating: 4, reviewCount: 13,
        description: "Form ống đứng kinh điển của quần denim, phù hợp với mọi vóc dáng và dễ dàng phối đồ. Chất liệu denim dày dặn, bền đẹp, mang lại vẻ ngoài nam tính và khỏe khoắn, là món đồ không thể thiếu trong tủ quần áo.",
        material: "Denim", form: "Straight",
        colors: [{ name: "Xanh jeans", hex: "#2243b6" }], sizes: ["S", "M", "L"]
    },
    {
        id: "nam-quandai-07",
        name: "Quần jeans nam dài recycled form cocoon",
        mainImage: "../../../admin/assets/images/Quan-jeans-nam-dai-recycled-form-cocoon.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-jeans-nam-dai-recycled-form-cocoon.webp",
            "../../../admin/assets/images/Quan-jeans-nam-dai-recycled-form-cocoon.webp",
            "../../../admin/assets/images/Quan-jeans-nam-dai-recycled-form-cocoon-3.webp",
            "../../../admin/assets/images/Quan-jeans-nam-dai-recycled-form-cocoon-4.webp",
            "../../../admin/assets/images/Quan-jeans-nam-dai-recycled-form-cocoon-5.webp",
            "../../../admin/assets/images/Quan-jeans-nam-dai-recycled-form-cocoon-6.webp"
        ],
        priceOld: 300000, priceNew: 260000, rating: 5, reviewCount: 22,
        description: "Quần jeans được sản xuất từ vật liệu tái chế, góp phần bảo vệ môi trường. Form cocoon độc đáo, mang lại sự thoải mái và phong cách riêng biệt, thích hợp cho những người yêu thời trang bền vững và muốn tạo sự khác biệt.",
        material: "Jeans tái chế", form: "Cocoon",
        colors: [{ name: "Xanh đen", hex: "#020216" }], sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nam-quandai-08",
        name: "Quần tây nam ôm Twill Texture form slim crop",
        mainImage: "../../../admin/assets/images/Quan-tay-nam-om-Twill-Texture-form-slim-crop.webp",
        thumbnailImages: [
            "../../../admin/assets/images/Quan-tay-nam-om-Twill-Texture-form-slim-crop.webp",
            "../../../admin/assets/images/Quan-tay-nam-om-Twill-Texture-form-slim-crop.webp",
            "../../../admin/assets/images/Quan-tay-nam-om-Twill-Texture-form-slim-crop-3.webp",
            "../../../admin/assets/images/Quan-tay-nam-om-Twill-Texture-form-slim-crop-4.webp"
            
        ],
        priceOld: 280000, priceNew: 250000, rating: 4, reviewCount: 16,
        description: "Quần tây ôm với bề mặt vải Twill Texture độc đáo, tạo nên vẻ ngoài lịch sự và năng động. Form slim crop tôn dáng, rất phù hợp cho những người theo đuổi phong cách công sở trẻ trung hoặc dạo phố thanh lịch, tiện lợi trong mọi hoạt động.",
        material: "Twill Texture", form: "Slim Crop",
        colors: [{ name: "Ghi", hex: "#808080" }], sizes: ["S", "M", "L", "XL"]
    },

    // ==== SẢN PHẨM NỮ - ĐẦM NỮ (dress.html) ====
    {
        id: "nu-dam-01",
        name: "Chiara Dress",
        mainImage: "../../../admin/assets/images/Chiara Dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Chiara Dress.jpg",
            "../../../admin/assets/images/Chiara Dress(2).jpg",
            "../../../admin/assets/images/Chiara Dress(3).jpg"            
        ],
        priceOld: 169000, priceNew: 149000, rating: 4, reviewCount: 20,
        description: "Chiara Dress là chiếc đầm xòe cổ điển với chi tiết nơ thắt eo tinh tế, tôn lên vẻ nữ tính và dịu dàng. Chất liệu nhẹ nhàng, thoáng mát, thích hợp cho những buổi tiệc nhẹ hay dạo phố mùa hè. Với thiết kế linh hoạt, đầm Chiara dễ dàng kết hợp cùng giày cao gót hoặc sandal, mang lại sự tự tin và thanh lịch.",
        material: "Cotton linen cao cấp", form: "Fitted at waist, A-line skirt",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-dam-02",
        name: "Đầm babydoll trễ vai tay dài phối nơ HYGGE - Peachy Dress",
        mainImage: "../../../admin/assets/images/Đầm babydoll trễ vai tay dài phối nơ HYGGE - Peachy Dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Đầm babydoll trễ vai tay dài phối nơ HYGGE - Peachy Dress.jpg",
            "../../../admin/assets/images/Đầm babydoll trễ vai tay dài phối nơ HYGGE(2) - Peachy Dress.jpg",
            "../../../admin/assets/images/Đầm babydoll trễ vai tay dài phối nơ HYGGE(3) - Peachy Dress.jpg"
        ],
        priceOld: 245000, priceNew: 215000, rating: 5, reviewCount: 15,
        description: "Peachy Dress mang phong cách babydoll đáng yêu với thiết kế trễ vai quyến rũ và tay dài điệu đà, nhấn nhá bằng chiếc nơ thắt duyên dáng. Chất liệu mềm mại, thoáng khí mang lại cảm giác thoải mái tối đa, hoàn hảo cho những buổi dạo chơi cuối tuần hoặc các sự kiện đặc biệt, thể hiện nét nữ tính và trẻ trung.",
        material: "Voan lụa", form: "Babydoll",
        colors: [{ name: "Hồng", hex: "#ffc0cb" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-dam-03",
        name: "Đầm ngắn tay chuông cổ cao HYGGE - Pandora Dress",
        mainImage: "../../../admin/assets/images/Đầm ngắn tay chuông cổ cao HYGGE - Pandora Dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Đầm ngắn tay chuông cổ cao HYGGE - Pandora Dress.jpg",
            "../../../admin/assets/images/Đầm ngắn tay chuông cổ cao HYGGE(2) - Pandora Dress.jpg",
            "../../../admin/assets/images/Đầm ngắn tay chuông cổ cao HYGGE(3) - Pandora Dress.jpg"
        ],
        priceOld: 329000, priceNew: 309000, rating: 5, reviewCount: 22,
        description: "Pandora Dress nổi bật với thiết kế cổ cao sang trọng và tay chuông độc đáo, tạo điểm nhấn đầy cuốn hút. Form đầm suông nhẹ nhàng, che khuyết điểm hiệu quả mà vẫn giữ được nét thanh lịch. Đây là lựa chọn tuyệt vời cho các buổi tiệc tối hoặc sự kiện trang trọng, giúp bạn nổi bật một cách tinh tế.",
        material: "Ren thêu", form: "Loose fit",
        colors: [{ name: "Xanh coban", hex: "#0047AB" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-dam-04",
        name: "Đầm tơ tay ngắn hạ eo bo chun họa tiêt ren HYGGE - Samantha Dress",
        mainImage: "../../../admin/assets/images/Đầm tơ tay ngắn hạ eo bo chun họa tiêt ren HYGGE - Samantha Dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Đầm tơ tay ngắn hạ eo bo chun họa tiêt ren HYGGE - Samantha Dress.jpg",
            "../../../admin/assets/images/Đầm tơ tay ngắn hạ eo bo chun họa tiêt ren HYGGE(2) - Samantha Dress.jpg",
            "../../../admin/assets/images/Đầm tơ tay ngắn hạ eo bo chun họa tiêt ren HYGGE(3) - Samantha Dress.jpg"

        ],
        priceOld: 375000, priceNew: 345000, rating: 4, reviewCount: 18,
        description: "Samantha Dress là sự kết hợp hoàn hảo giữa chất liệu tơ bay bổng và họa tiết ren tinh xảo, tạo nên vẻ ngoài nhẹ nhàng, bay bổng. Thiết kế hạ eo bo chun không chỉ thoải mái mà còn giúp tôn lên vòng eo duyên dáng. Phù hợp cho những buổi hẹn hò lãng mạn hoặc dạo chơi cùng bạn bè, mang đến vẻ đẹp bay bổng.",
        material: "Tơ & Ren", form: "Empire Waist",
        colors: [{ name: "Be", hex: "#F5F5DC" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-dam-05",
        name: "Flurry Dress",
        mainImage: "../../../admin/assets/images/Flurry Dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Flurry Dress.jpg",
            "../../../admin/assets/images/Flurry Dress(2).jpg",
            "../../../admin/assets/images/Flurry Dress(3).jpg"
        ],
        priceOld: 269000, priceNew: 249000, rating: 5, reviewCount: 25,
        description: "Flurry Dress với thiết kế hiện đại, đường cắt xẻ táo bạo nhưng vẫn giữ được nét tinh tế. Chất liệu co giãn nhẹ, ôm vừa vặn, tôn lên vóc dáng thanh mảnh của người mặc. Lý tưởng cho những buổi tiệc đêm hoặc sự kiện đòi hỏi phong cách cá tính, thu hút, đảm bảo bạn sẽ nổi bật.",
        material: "Chun", form: "Bodycon",
        colors: [{ name: "Caro", hex: "#000000" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-dam-06",
        name: "Liltee Dress",
        mainImage: "../../../admin/assets/images/Liltee Dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Liltee Dress.jpg",
            "../../../admin/assets/images/Liltee Dress(2).jpg",
            "../../../admin/assets/images/Liltee Dress(3).jpg",
        ],
        priceOld: 199000, priceNew: 185000, rating: 4, reviewCount: 12,
        description: "Liltee Dress mang phong cách tối giản với phom dáng suông rộng rãi, tạo sự thoải mái tối đa khi mặc. Chiếc đầm này phù hợp cho nhiều dịp, từ đi học, đi làm đến dạo phố. Dễ dàng kết hợp với phụ kiện để tạo nên nhiều phong cách khác nhau, là một món đồ đa năng trong tủ đồ.",
        material: "Cotton cao cấp", form: "Oversized",        
        colors: [{ name: "Trắng", hex: "#FFFFFF" }, { name: "Đen", hex: "#000000" }],
        sizes: ["Free size"]
    },
    {
        id: "nu-dam-07",
        name: "LINDA DRESS",
        mainImage: "../../../admin/assets/images/LINDA DRESS.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/LINDA DRESS.jpg",
            "../../../admin/assets/images/LINDA DRESS2.jpg",
            "../../../admin/assets/images/LINDA DRESS3.jpg"
        ],
        priceOld: 399000, priceNew: 379000, rating: 5, reviewCount: 30,
        description: "Linda Dress là chiếc đầm dạ hội sang trọng với thiết kế lấp lánh và đường xẻ tà quyến rũ. Chất liệu cao cấp và đường may tinh xảo đảm bảo bạn sẽ tỏa sáng trong mọi bữa tiệc, khẳng định đẳng cấp và phong thái của mình.",
        material: "Sequins & Voan", form: "Maxi, High Slit",
        colors: [{ name: "Xanh nhạt", hex: "#3EAEF4" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-dam-08",
        name: "Oscar Midi Dress",
        mainImage: "../../../admin/assets/images/Oscar Midi Dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Oscar Midi Dress.jpg",
            "../../../admin/assets/images/Oscar Midi Dress(2).jpg",
            "../../../admin/assets/images/Oscar Midi Dress(3).jpg"
        ],
        priceOld: 319000, priceNew: 299000, rating: 4, reviewCount: 15,
        description: "Oscar Midi Dress mang phong cách thanh lịch với độ dài midi vừa phải, tôn lên vóc dáng và phù hợp cho môi trường công sở hoặc các buổi họp mặt quan trọng. Thiết kế cổ V duyên dáng và chất liệu co giãn nhẹ giúp bạn thoải mái cả ngày dài, là lựa chọn hoàn hảo cho sự chuyên nghiệp.",
        material: "Viscose cao cấp", form: "Midi",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-dam-09",
        name: "Polo Dress",
        mainImage: "../../../admin/assets/images/polo dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/polo dress.jpg",
            "../../../admin/assets/images/polo dress(2).jpg"

        ],
        priceOld: 169000, priceNew: 149000, rating: 4, reviewCount: 20,
        description: "Polo Dress kết hợp sự năng động của áo polo và vẻ nữ tính của chiếc đầm. Thiết kế basic, chất liệu cotton mềm mại, phù hợp cho những ngày đi chơi, dạo phố hoặc các hoạt động ngoại khóa, mang đến vẻ ngoài thoải mái và trẻ trung.",
        material: "Cotton Pique", form: "A-line",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-dam-10",
        name: "Prim Dress",
        mainImage: "../../../admin/assets/images/prim dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/prim dress.jpg",
            "../../../admin/assets/images/prim dress(2).jpg",
            "../../../admin/assets/images/prim dress(3).jpg",
            
        ],
        priceOld: 319000, priceNew: 299000, rating: 5, reviewCount: 28,
        description: "Prim Dress là biểu tượng của sự sang trọng và cổ điển. Thiết kế tôn dáng, đường may tỉ mỉ cùng chi tiết cut-out tinh tế tạo nên một bộ cánh hoàn hảo cho các sự kiện trang trọng hay tiệc tùng đêm, giúp bạn tự tin khoe dáng.",
        material: "Lụa satin", form: "Slim fit",
        colors: [{ name: "Xanh lá", hex: "#0D2818" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-dam-11",
        name: "Rosie Dress",
        mainImage: "../../../admin/assets/images/rosie dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/rosie dress.jpg",
            "../../../admin/assets/images/rosie dress(2).jpg",
            "../../../admin/assets/images/rosie dress(3).jpg"
        ],
        priceOld: 259000, priceNew: 239000, rating: 5, reviewCount: 22,
        description: "Rosie Dress với họa tiết hoa hồng dịu dàng, mang đến vẻ đẹp ngọt ngào và lãng mạn. Thiết kế tay phồng nhẹ và chi tiết bèo nhún tạo nét bay bổng, rất phù hợp cho những buổi hẹn hò hoặc dã ngoại, tạo điểm nhấn đầy cuốn hút.",
        material: "Voan hoa", form: "Maxi flowy",
        colors: [{ name: "Hồng pastel", hex: "#FFD1DC" }],
        sizes: ["Free size"]
    },
    {
        id: "nu-dam-12",
        name: "Venis Dress",
        mainImage: "../../../admin/assets/images/venis dress.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/venis dress.jpg",
            "../../../admin/assets/images/venis dress.jpg(3)",
            "../../../admin/assets/images/venis dress(2).jpg"
        ],
        priceOld: 199000, priceNew: 179000, rating: 4, reviewCount: 17,
        description: "Venis Dress đơn giản nhưng không kém phần cuốn hút, với chất liệu thun co giãn ôm sát, tôn lên đường cong. Đây là item lý tưởng cho những buổi gặp gỡ bạn bè hay các hoạt động đòi hỏi sự năng động và thoải mái, là lựa chọn đa năng cho tủ đồ của bạn.",
        material: "Thun cotton", form: "Bodycon midi",
        colors: [{ name: "Xanh dương", hex: "#3EAEF4" }],
        sizes: ["S", "M"]
    },

    // ==== SẢN PHẨM NỮ - QUẦN NỮ (pants.html) ====
    {
        id: "nu-quan-01",
        name: "Bonew Parachute Short",
        mainImage: "../../../admin/assets/images/Bonew Parachute Short.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Bonew Parachute Short.jpg",
            "../../../admin/assets/images/Bonew Parachute Short(2).jpg"
        ],
        priceOld: 169000, priceNew: 149000, rating: 4, reviewCount: 18,
        description: "Quần short parachute độc đáo với thiết kế túi hộp và dáng rộng rãi, mang lại vẻ ngoài năng động và cá tính. Chất liệu nhẹ, nhanh khô, phù hợp cho những chuyến đi khám phá hoặc phong cách streetwear, dễ dàng kết hợp cho các outfit cá tính.",
        material: "Dù polyester", form: "Baggy shorts",
        colors: [{ name: "Kem", hex: "#FFFDD0" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-quan-02",
        name: "Fomos Jean Short Dark Blue",
        mainImage: "../../../admin/assets/images/Fomos Jean Short dark blue.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Fomos Jean Short dark blue.jpg",
            "../../../admin/assets/images/Fomos Jean Short dark blue(2).jpg"

        ],
        priceOld: 189000, priceNew: 169000, rating: 5, reviewCount: 22,
        description: "Quần short jean tối màu basic, dễ dàng kết hợp với mọi loại áo và phụ kiện. Chất liệu jean co giãn nhẹ, tạo sự thoải mái khi vận động. Đây là item không thể thiếu trong tủ đồ hàng ngày của mọi cô gái năng động.",
        material: "Jean Cotton", form: "Straight fit",
        colors: [{ name: "Xanh đen", hex: "#1F2833" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-quan-03",
        name: "Hebe Jeans",
        mainImage: "../../../admin/assets/images/hebe jeans.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/hebe jeans.jpg",
            "../../../admin/assets/images/hebe jeans.jpg(2)",
            "../../../admin/assets/images/hebe jeans.jpg(3)"

        ],
        priceOld: 219000, priceNew: 189000, rating: 4, reviewCount: 15,
        description: "Hebe Jeans là mẫu quần jean ống suông phá cách, mang đến phong cách vintage cá tính. Các chi tiết rách nhẹ và màu wash bạc tạo vẻ ngoài sành điệu, độc đáo. Thích hợp cho những cô nàng muốn thể hiện phong cách riêng, không ngại thử thách.",
        material: "Jean Denim", form: "Straight loose",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-quan-04",
        name: "Pamin Pants",
        mainImage: "../../../admin/assets/images/Pamin Pants.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Pamin Pants.jpg",
            "../../../admin/assets/images/Pamin Pants.jpg2",
            "../../../admin/assets/images/Pamin Pants.jpg3"


        ],
        priceOld: 249000, priceNew: 229000, rating: 5, reviewCount: 19,
        description: "Pamin Pants với form quần ống rộng thanh lịch, chất liệu vải mềm rũ, tạo cảm giác thoải mái và che khuyết điểm tốt. Thiết kế đơn giản nhưng sang trọng, lý tưởng cho môi trường công sở hoặc những buổi hẹn hò, là sự lựa chọn hoàn hảo cho vẻ ngoài tinh tế.",
        material: "Vải Linen", form: "Wide-leg",
        colors: [{ name: "Xanh da trời", hex: "#87CEEB" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-quan-05",
        name: "Pull Pants",
        mainImage: "../../../admin/assets/images/pull pants.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/pull pants.jpg",
            "../../../admin/assets/images/pull pants(2).jpg",
            "../../../admin/assets/images/pull pants(3).jpg",
            "../../../admin/assets/images/pull pants(4).jpg"
        ],
        priceOld: 239000, priceNew: 219000, rating: 4, reviewCount: 16,
        description: "Pull Pants là quần tây kiểu dáng classic với độ ôm vừa phải, tôn dáng. Chất liệu co giãn nhẹ, tạo sự thoải mái tối đa khi mặc và di chuyển. Một lựa chọn hoàn hảo cho phong cách công sở chuyên nghiệp và lịch sự, không lỗi mốt.",
        material: "Vải Twill", form: "Slim fit",
        colors: [{ name: "Xanh jeans", hex: "#2243b6" }], sizes: ["S", "M", "L"]
    },
    {
        id: "nu-quan-06",
        name: "Strip Long Pants",
        mainImage: "../../../admin/assets/images/Strip Long Pants.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Strip Long Pants.jpg",
            "../../../admin/assets/images/Strip Long Pants(2).jpg",
            "../../../admin/assets/images/Strip Long Pants(33).jpg"


        ],
        priceOld: 269000, priceNew: 249000, rating: 5, reviewCount: 20,
        description: "Strip Long Pants với họa tiết sọc đứng, tạo hiệu ứng kéo dài chân, mang lại vẻ ngoài cao ráo và thanh thoát. Chất liệu vải mềm mại, ít nhăn, phù hợp cho phong cách công sở thanh lịch hoặc dạo phố trẻ trung, tạo điểm nhấn cho bộ trang phục.",
        material: "Cotton pha", form: "Straight fit",
        colors: [{ name: "Xám", hex: "#808080" }],
        sizes: ["S", "M", "L"]
    },

    // ==== SẢN PHẨM NỮ - VÁY NỮ (skirt.html) ====
    {
        id: "nu-vay-01",
        name: "Bubble Skirt",
        mainImage: "../../../admin/assets/images/bubble skirt.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/bubble skirt.jpg",
            "../../../admin/assets/images/bubble skirt(22).jpg",
            "../../../admin/assets/images/bubble skirt(3).jpg"

        ],
        priceOld: 189000, priceNew: 169000, rating: 4, reviewCount: 15,
        description: "Bubble Skirt mang đến phong cách độc đáo với phom dáng phồng đáng yêu, tạo cảm giác bay bổng và ngọt ngào. Chất liệu nhẹ, đường may tinh tế, phù hợp cho những cô nàng muốn thể hiện sự dễ thương, trẻ trung, và nổi bật.",
        material: "Lụa gấm", form: "Bubble",
        colors: [{ name: "Xám", hex: "#808080" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-vay-02",
        name: "Doris Midi Skirt",
        mainImage: "../../../admin/assets/images/Doris Midi Skirt.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Doris Midi Skirt.jpg",
            "../../../admin/assets/images/Doris Midi Skirt3.jpg",
            "../../../admin/assets/images/Doris Midi Skirt2.jpg"


        ],
        priceOld: 245000, priceNew: 215000, rating: 5, reviewCount: 18,
        description: "Chân váy midi thanh lịch, phù hợp cho môi trường công sở hoặc những buổi hẹn hò lãng mạn. Với độ dài midi vừa phải, chiếc váy này giúp tôn lên vóc dáng mà vẫn giữ được sự kín đáo, tinh tế, mang lại vẻ đẹp chuyên nghiệp và duyên dáng.",
        material: "Vải Twill", form: "A-line midi",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-vay-03",
        name: "Ficus Skirt",
        mainImage: "../../../admin/assets/images/ficus skirt.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/ficus skirt.jpg",
            "../../../admin/assets/images/ficus skirt(2).jpg",
            "../../../admin/assets/images/ficus skirt(33).jpg"
        ],
        priceOld: 269000, priceNew: 239000, rating: 5, reviewCount: 20,
        description: "Ficus Skirt với họa tiết kẻ caro lớn độc đáo, mang đến phong cách vintage cá tính. Chất liệu vải dày dặn, đứng form, thích hợp cho cả mùa hè và mùa thu, dễ dàng kết hợp với áo len hoặc áo thun để tạo nên nhiều phong cách.",
        material: "Dạ kẻ", form: "A-line",
        colors: [{ name: "Xám", hex: "#808080" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-vay-04",
        name: "Hansy Mini Skirt",
        mainImage: "../../../admin/assets/images/Hansy Mini Skirt.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Hansy Mini Skirt.jpg",
            "../../../admin/assets/images/Hansy Mini Skirt2.jpg",
            "../../../admin/assets/images/Hansy Mini Skirt3.jpg"
        ],
        priceOld: 289000, priceNew: 259000, rating: 4, reviewCount: 12,
        description: "Chân váy mini phong cách học đường với thiết kế xếp ly tinh xảo, mang lại vẻ ngoài năng động và trẻ trung. Có thể dễ dàng kết hợp với áo sơ mi, áo thun để tạo nhiều phong cách khác nhau, lý tưởng cho những ngày đi học hay đi chơi.",
        material: "Vải kaki", form: "Pleated mini",
        colors: [{ name: "Be", hex: "#F5F5DC" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-vay-05",
        name: "Hiba Skirt",
        mainImage: "../../../admin/assets/images/Hiba Skirt.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Hiba Skirt.jpg",
            "../../../admin/assets/images/Hiba Skirt2.jpg",
            "../../../admin/assets/images/Hiba Skirt3.jpg"
        ],
        priceOld: 249000, priceNew: 219000, rating: 5, reviewCount: 17,
        description: "Hiba Skirt với dáng xòe nhẹ nhàng, chất liệu voan bay bổng, tạo cảm giác nữ tính và thanh thoát. Váy phù hợp cho những buổi dạo chơi, đi cafe cùng bạn bè. Dễ dàng phối với áo blouse hoặc crop top, mang đến vẻ đẹp nhẹ nhàng, tinh khôi.",
        material: "Voan lụa", form: "A-line flared",
        colors: [{ name: "Hồng", hex: "#ffc0cb" }],
        sizes: ["Free size"]
    },
    {
        id: "nu-vay-06",
        name: "Nixie Pleated Skirt",
        mainImage: "../../../admin/assets/images/Nixie Pleated Skirt.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Nixie Pleated Skirt.jpg",
            "../../../admin/assets/images/Nixie Pleated Skirt2.jpg",
            "../../../admin/assets/images/Nixie Pleated Skirt3.jpg",
            "../../../admin/assets/images/Nixie Pleated Skirt(2).jpg",
            "../../../admin/assets/images/Nixie Pleated Skirt(3).jpg"
        ],
        priceOld: 319000, priceNew: 299000, rating: 5, reviewCount: 22,
        description: "Chân váy xếp ly hiện đại, tạo nên vẻ ngoài uyển chuyển và duyên dáng. Chất liệu cao cấp giữ form tốt, không dễ nhăn. Có thể kết hợp với áo blazer cho phong cách công sở hoặc áo len cho mùa lạnh, là lựa chọn đa năng và thời trang.",
        material: "Vải Tweed", form: "Pleated midi",
        colors: [{ name: "Be", hex: "#F5F5DC" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-vay-07",
        name: "Rolly Bubble Skirt",
        mainImage: "../../../admin/assets/images/Rolly Bubble Skirt.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Rolly Bubble Skirt.jpg",
            "../../../admin/assets/images/Rolly Bubble Skirt2.jpg",
            "../../../admin/assets/images/Rolly Bubble Skirt3.jpg"


        ],
        priceOld: 229000, priceNew: 199000, rating: 4, reviewCount: 14,
        description: "Phiên bản Bubble Skirt độc đáo với các đường gấp nếp nổi bật, tạo hiệu ứng bong bóng ấn tượng. Chất liệu đứng dáng, giữ phom tốt, giúp bạn luôn nổi bật và thu hút trong mọi sự kiện, thể hiện phong cách riêng biệt.",
        material: "Vải Polyester pha", form: "Bubble",
        colors: [{ name: "Đen", hex: "#000000" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-vay-08",
        name: "Tacha Bubble Skirt",
        mainImage: "../../../admin/assets/images/Tacha Bubble Skirt.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Tacha Bubble Skirt.jpg",
            "../../../admin/assets/images/Tacha Bubble Skirt2.jpg"

        ],
        priceOld: 259000, priceNew: 229000, rating: 5, reviewCount: 19,
        description: "Một phong cách Bubble Skirt khác với chất liệu cao cấp hơn, mang lại vẻ ngoài sang trọng và độc đáo. Phần chân váy phồng tạo điểm nhấn, phù hợp cho những sự kiện đòi hỏi phong cách đặc biệt, giúp bạn tỏa sáng đầy cá tính.",
        material: "Phi bóng", form: "Bubble",
        colors: [{ name: "Đen", hex: "#000000" }],
        sizes: ["S", "M"]
    },

    // ==== SẢN PHẨM NỮ - TOPS NỮ (tops.html) ====
    {
        id: "nu-tops-01",
        name: "Áo gile phối nơ cổ V DUO STYLE - Rebel Gile",
        mainImage: "../../../admin/assets/images/Áo gile phối nơ cổ V DUO STYLE - Rebel Gile.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Áo gile phối nơ cổ V DUO STYLE - Rebel Gile.jpg", 
            "../../../admin/assets/images/Áo gile phối nơ cổ V DUO STYLE(2) - Rebel Gile.jpg", 
            "../../../admin/assets/images/Áo gile phối nơ cổ V DUO STYLE(3) - Rebel Gile.jpg", 
            "../../../admin/assets/images/Áo gile phối nơ cổ V DUO STYLE(4) - Rebel Gile.jpg", 
            "../../../admin/assets/images/Áo gile phối nơ cổ V DUO STYLEE(5) - Rebel Gile.jpg"

        ],
        priceOld: 219000, priceNew: 199000, rating: 4, reviewCount: 18,
        description: "Áo gile phối nơ cổ V DUO STYLE - Rebel Gile là sự kết hợp đột phá giữa nét cổ điển và phong cách đường phố cá tính, dành cho những người trẻ ưa thích sự khác biệt. Chiếc áo gile với thiết kế cổ V sắc sảo, điểm nhấn là chiếc nơ đi kèm tinh tế, tạo nên vẻ ngoài vừa lịch lãm vừa phá cách, là điểm nhấn ấn tượng cho mọi bộ trang phục.",
        material: "Cotton cao cấp", form: "Regular fit",
        colors: [{ name: "Đỏ", hex: "#ff0000" }, { name: "Trắng", hex: "#FFFFFF" }], 
        sizes: ["S", "M", "L", "XL"]
    },
    {
        id: "nu-tops-02",
        name: "Áo kiểu Jamie shirt HYGGE",
        mainImage: "../../../admin/assets/images/Áo kiểu Jamie shirt HYGGE - MS01.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Áo kiểu Jamie shirt HYGGE - MS01.jpg",
            "../../../admin/assets/images/Áo kiểu Jamie shirt HYGGE(2) - MS01.jpg",
            "../../../admin/assets/images/Áo kiểu Jamie shirt HYGGE(3) - MS01.jpg"
        
        ],
        priceOld: 169000, priceNew: 149000, rating: 5, reviewCount: 25,
        description: "Jamie shirt là mẫu áo kiểu basic với thiết kế thanh lịch và tối giản. Chất liệu vải nhẹ, thoáng mát, mang lại cảm giác thoải mái tối đa khi mặc. Áo dễ dàng phối hợp với nhiều kiểu trang phục, từ quần jean đến chân váy, phù hợp cho mọi hoạt động hàng ngày và luôn mang đến vẻ đẹp tinh tế.",
        material: "Cotton Poplin", form: "Loose fit",
        colors: [{ name: "Xám chì", hex: "#6699CC" }],
        sizes: ["Free size"]
    },
    {
        id: "nu-tops-03",
        name: "Áo kiểu Rebel gile DUO STYLE",
        mainImage: "../../../admin/assets/images/Áo kiểu Rebel gile DUO STYLE - MS01.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Áo kiểu Rebel gile DUO STYLE - MS01.jpg",
            "../../../admin/assets/images/Áo kiểu Rebel gile DUO STYLE(2) - MS01.jpg",
            "../../../admin/assets/images/Áo kiểu Rebel gile DUO STYLE(3) - MS01.jpg"

        ],
        priceOld: 259000, priceNew: 229000, rating: 5, reviewCount: 20,
        description: "Chiếc gile này là một món đồ độc đáo trong tủ quần áo, với thiết kế hiện đại và đường cắt may tinh xảo. Áo dễ dàng tạo điểm nhấn cho bộ trang phục, từ phong cách thanh lịch đến cá tính, thể hiện gu thời trang độc đáo của bạn.",
        material: "Viscose cao cấp", form: "Structured fit",
        colors: [{ name: "TrắngTrắng", hex: "#ffffff" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-tops-04",
        name: "Áo kiểu tay dài hoa nổi DUO STYLE - Esther Top",
        mainImage: "../../../admin/assets/images/Áo kiểu tay dài hoa nổi DUO STYLE - Esther Top.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Áo kiểu tay dài hoa nổi DUO STYLE - Esther Top.jpg",
            "../../../admin/assets/images/Áo kiểu tay dài hoa nổi DUO STYLE(2) - Esther Top.jpg",
            "../../../admin/assets/images/Áo kiểu tay dài hoa nổi DUO STYLE(3) - Esther Top.jpg"

        ],
        priceOld: 249000, priceNew: 229000, rating: 4, reviewCount: 16,
        description: "Esther Top với họa tiết hoa nổi 3D tinh xảo, tạo nên vẻ ngoài lãng mạn và bay bổng. Tay dài bồng bềnh mang lại nét duyên dáng, cổ áo cách điệu tôn lên vẻ đẹp nữ tính. Lý tưởng cho những buổi tiệc nhẹ hoặc hẹn hò lãng mạn, giúp bạn tỏa sáng theo cách riêng.",
        material: "Tơ & Voan hoa nổi", form: "Loose fit",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-tops-05",
        name: "Áo kiểu tay phồng cột nơ HYGGE - Naomi Top",
        mainImage: "../../../admin/assets/images/Áo kiểu tay phồng cột nơ HYGGE - Naomi Top.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Áo kiểu tay phồng cột nơ HYGGE - Naomi Top.jpg",
            "../../../admin/assets/images/Áo kiểu tay phồng cột nơ HYGGE (2)- Naomi Top.jpg",
            "../../../admin/assets/images/Áo kiểu tay phồng cột nơ HYGGE (3)- Naomi Top.jpg"


        ],
        priceOld: 209000, priceNew: 179000, rating: 5, reviewCount: 18,
        description: "Naomi Top với tay phồng bồng bềnh và chi tiết cột nơ duyên dáng ở cổ tay, mang đến vẻ ngoài ngọt ngào và tiểu thư. Chất liệu nhẹ nhàng, mềm mại, thoải mái khi mặc. Phù hợp cho những buổi dạo phố hay cafe cùng bạn bè, tạo nên sự duyên dáng khó cưỡng.",
        material: "Voan lụa", form: "Loose fit",
        colors: [{ name: "TrắngTrắng", hex: "#FFFFFFFFFF" }],
        sizes: ["Free size"]
    },
    {
        id: "nu-tops-06",
        name: "Áo tay ngắn cổ thủy thủ DUO STYLE - Ayako Top",
        mainImage: "../../../admin/assets/images/Áo tay ngắn cổ thủy thủ DUO STYLE - Ayako Top.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Áo tay ngắn cổ thủy thủ DUO STYLE - Ayako Top.jpg",
            "../../../admin/assets/images/Áo tay ngắn cổ thủy thủ DUO STYLE(2) - Ayako Top.jpg",
            "../../../admin/assets/images/Áo tay ngắn cổ thủy thủ DUO STYLE(3) - Ayako Top.jpg"


        ],
        priceOld: 319000, priceNew: 299000, rating: 5, reviewCount: 22,
        description: "Ayako Top mang phong cách thủy thủ cổ điển với cổ áo lớn và đường viền tương phản, tạo điểm nhấn độc đáo. Áo có chất liệu mềm mại, thoáng mát, là lựa chọn tuyệt vời cho những ai yêu thích phong cách retro năng động, mang đến vẻ ngoài cá tính.",
        material: "Cotton & Polyester", form: "Regular fit",
        colors: [{ name: "Xanh trắng", hex: "#ADD8E6" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-tops-07",
        name: "Betty Off-ShoulderTop",
        mainImage: "../../../admin/assets/images/Betty Off-ShoulderTop.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Betty Off-ShoulderTop.jpg",
            "../../../admin/assets/images/Betty Off-ShoulderTop2.jpg",
            "../../../admin/assets/images/Betty Off-ShoulderTop3.jpg"

        ],
        priceOld: 179000, priceNew: 145000, rating: 4, reviewCount: 14,
        description: "Betty Off-ShoulderTop với thiết kế trễ vai gợi cảm, tôn lên vẻ đẹp quyến rũ của đôi vai. Chất liệu co giãn nhẹ, ôm vừa vặn, tạo sự thoải mái khi mặc. Lý tưởng cho những buổi dạo phố, đi biển hay các buổi tiệc nhỏ, giúp bạn tự tin và nổi bật.",
        material: "Ribbed Knit", form: "Slim fit",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-tops-08",
        name: "Icy top",
        mainImage: "../../../admin/assets/images/icy top.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/icy top.jpg",
            "../../../admin/assets/images/icy top(2).jpg",
            "../../../admin/assets/images/icy top(3).jpg",
            "../../../admin/assets/images/icy top(4).jpg",
            "../../../admin/assets/images/icy top(5).jpg"


        ],
        priceOld: 299000, priceNew: 269000, rating: 5, reviewCount: 19,
        description: "Icy Top mang đến sự sang trọng và tinh tế với chất liệu satin óng ánh và đường cắt xẻ tinh tế. Thiết kế này tạo nên vẻ ngoài lôi cuốn và đẳng cấp, phù hợp cho những buổi tiệc tối hoặc sự kiện đặc biệt, là lựa chọn hoàn hảo để gây ấn tượng.",
        material: "Satin", form: "Structured crop",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }, { name: "Vàng", hex: "#B8860B" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-tops-09",
        name: "Kling Top",
        mainImage: "../../../admin/assets/images/Kling Top.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Kling Top.jpg",
            "../../../admin/assets/images/Kling Top(3).jpg"

        ],
        priceOld: 239000, priceNew: 215000, rating: 5, reviewCount: 17,
        description: "Kling Top với thiết kế bất đối xứng độc đáo, tạo nên vẻ ngoài phá cách và hiện đại. Chất liệu co giãn ôm vừa vặn, giúp bạn nổi bật giữa đám đông. Có thể kết hợp với quần jean hoặc chân váy để tạo phong cách năng động và cá tính, là món đồ độc đáo cho tủ quần áo.",
        material: "Thun gân", form: "Asymmetric",
        colors: [{ name: "Xám", hex: "#36454F" }],
        sizes: ["S", "M", "L"]
    },
    {
        id: "nu-tops-10",
        name: "Marisa Top",
        mainImage: "../../../admin/assets/images/Marisa Top.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Marisa Top.jpg",
            "../../../admin/assets/images/Marisa Top(2).jpg"
        ],
        priceOld: 229000, priceNew: 190000, rating: 4, reviewCount: 15,
        description: "Marisa Top với tay áo phồng nhẹ nhàng và chi tiết cổ vuông tinh tế, mang lại vẻ đẹp cổ điển và nữ tính. Chất liệu nhẹ, thoáng mát, phù hợp cho những buổi dạo phố, hẹn hò hoặc đi làm, mang đến vẻ ngoài dịu dàng và cuốn hút.",
        material: "Voan hoa", form: "Puff Sleeve",
        colors: [{ name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-tops-11",
        name: "Riso Tube Top",
        mainImage: "../../../admin/assets/images/Riso Tube Top.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Riso Tube Top.jpg",
            "../../../admin/assets/images/Riso Tube Top2.jpg",
            "../../../admin/assets/images/Riso Tube Top3.jpg"
        ],
        priceOld: 245000, priceNew: 215000, rating: 5, reviewCount: 20,
        description: "Riso Tube Top là chiếc áo quây ôm sát, khoe trọn bờ vai gợi cảm, mang đến vẻ ngoài năng động và quyến rũ. Chất liệu co giãn, thoải mái khi mặc. Có thể mặc kèm áo khoác ngoài hoặc diện riêng với quần jean cạp cao, là lựa chọn hoàn hảo cho phong cách cá tính.",
        material: "Cotton Spandex", form: "Fitted tube",
        colors: [{ name: "Đen", hex: "#000000" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-tops-12",
        name: "Romie Cami Top",
        mainImage: "../../../admin/assets/images/Romie Cami Top.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Romie Cami Top.jpg",
            "../../../admin/assets/images/Romie Cami Top(2).jpg"
        ],
        priceOld: 215000, priceNew: 185000, rating: 4, reviewCount: 16,
        description: "Romie Cami Top với thiết kế hai dây đơn giản nhưng thanh lịch, phù hợp cho mùa hè. Chất liệu lụa satin mềm mại, tạo cảm giác sang trọng và mát mẻ khi mặc. Có thể mặc trong blazer hoặc kết hợp với quần short để đi chơi, mang đến vẻ ngoài tinh tế và gợi cảm.",
        material: "Lụa Satin", form: "Regular cami",
        colors: [ { name: "Trắng", hex: "#FFFFFF" }],
        sizes: ["S", "M"]
    },
    {
        id: "nu-tops-13",
        name: "Timo Jacket",
        mainImage: "../../../admin/assets/images/Timo Jacket.jpg",
        thumbnailImages: [
            "../../../admin/assets/images/Timo Jacket.jpg",
            "../../../admin/assets/images/Timo Jacket(2).jpg",
            "../../../admin/assets/images/Timo Jacket(33).jpg"


        ],
        priceOld: 259000, priceNew: 229000, rating: 5, reviewCount: 21,
        description: "Timo Jacket là áo khoác blazer kiểu lửng thời trang, mang đến vẻ ngoài năng động và sành điệu. Chất liệu đứng dáng, giữ phom tốt. Phù hợp cho những buổi đi chơi, dạo phố, dễ dàng phối hợp với các loại áo crop top bên trong, tạo nên outfit đầy cá tính.",
        material: "Vải Polyester", form: "Cropped Blazer",
        colors: [{ name: "Kem", hex: "#FFFDD0" }],
        sizes: ["S", "M", "L"]
    },
];