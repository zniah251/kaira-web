-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 11:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-web`
--

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `title` varchar(350) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `thumbnail` varchar(500) NOT NULL,
  `thumbnail2` varchar(500) DEFAULT NULL,
  `thumbnail3` varchar(500) DEFAULT NULL,
  `description` longtext NOT NULL,
  `stock` int(11) NOT NULL,
  `size` varchar(2) NOT NULL,
  `size2` varchar(2) DEFAULT NULL,
  `size3` varchar(2) DEFAULT NULL,
  `rating` float NOT NULL,
  `sold` int(11) NOT NULL,
  `color` varchar(30) NOT NULL,
  `color2` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pid`, `cid`, `title`, `price`, `discount`, `thumbnail`, `thumbnail2`, `thumbnail3`, `description`, `stock`, `size`, `size2`, `size3`, `rating`, `sold`, `color`, `color2`) VALUES
(1, 7, 'Áo polo cổ bé tay ngắn in hoạ tiết chữ hiện đại', 199000.00, 159000.00, 'ao-polo-co-be-tay-ngan-in-hoa-tiet-chU-hien-ai.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Đen', NULL),
(2, 7, 'Áo polo cổ bé tay ngắn in hoạ tiết chữ hiện đại', 210000.00, 180000.00, 'ao-polo-co-be-tay-ngan-in-hoa-tiet-chU-hien-ai.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Đen', NULL),
(3, 7, 'Áo polo nam cổ bé tay ngắn trẻ trung', 160000.00, 140000.00, 'ao-polo-nam-co-be-tay-ngan-tre-trung.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 4.5, 0, 'Cam', NULL),
(4, 7, 'Áo polo nam cổ bé tay ngắn trẻ trung', 170000.00, 150000.00, 'ao-polo-nam-co-be-tay-ngan-tre-trung.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 4.5, 0, 'Cam', NULL),
(5, 7, 'Áo polo ngắn tay nam S.cafe phối cổ.fitted', 299000.00, 269000.00, 'ao-polo-ngan-tay-nam-S.Cafe-phoi-co.Fitted.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 4.5, 0, 'Trắng', NULL),
(6, 7, 'Áo polo ngắn tay nam S.cafe phối cổ.fitted', 319000.00, 289000.00, 'ao-polo-ngan-tay-nam-S.Cafe-phoi-co.Fitted.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 4.5, 0, 'Trắng', NULL),
(7, 7, 'Áo polo cổ bé tay ngắn thời thượng', 249000.00, 239000.00, 'ao-polo-co-be-tay-ngan-thoi-thuong.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Trắng', NULL),
(8, 7, 'Áo polo cổ bé tay ngắn thời thượng', 279000.00, 269000.00, 'ao-polo-co-be-tay-ngan-thoi-thuong.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Trắng', NULL),
(9, 7, 'Áo polo nam ngắn tay cotton', 199000.00, 159000.00, 'ao-polo-nam-ngan-tay-cotton.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 4.5, 0, 'Đen', NULL),
(10, 7, 'Áo polo nam ngắn tay cotton', 219000.00, 179000.00, 'ao-polo-nam-ngan-tay-cotton.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 4.5, 0, 'Đen', NULL),
(11, 7, 'Áo polo nam premium 100% cotton phối sọc form fitt', 310000.00, 300000.00, 'ao-polo-nam-premium-100_-cotton-phoi-soc-form-fitt.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 20, 'L', NULL, NULL, 4.8, 0, 'Trắng', NULL),
(12, 7, 'Áo polo nam premium 100% cotton phối sọc form fitt', 330000.00, 310000.00, 'ao-polo-nam-premium-100_-cotton-phoi-soc-form-fitt.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 20, 'XL', NULL, NULL, 4.8, 0, 'Trắng', NULL),
(13, 7, 'Áo polo nam thời trang basic dễ phối đồ', 149000.00, 129000.00, 'ao-polo-nam-thoi-trang-basic-de-phoi-o.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 15, 'L', NULL, NULL, 4.5, 0, 'Nâu', NULL),
(14, 7, 'Áo polo nam thời trang basic dễ phối đồ', 169000.00, 149000.00, 'ao-polo-nam-thoi-trang-basic-de-phoi-o.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 15, 'XL', NULL, NULL, 4.5, 0, 'Nâu', NULL),
(15, 7, 'Áo polo nam trơn vải cotton polyester', 299000.00, 269000.00, 'ao-polo-nam-tron-vai-cotton-polyester.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 30, 'L', NULL, NULL, 4.6, 0, 'Trắng', NULL),
(16, 7, 'Áo polo nam trơn vải cotton polyester', 319000.00, 289000.00, 'ao-polo-nam-tron-vai-cotton-polyester.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 30, 'XL', NULL, NULL, 4.6, 0, 'Trắng', NULL),
(17, 7, 'Áo thun ngắn tay nam', 189000.00, 159000.00, 'ao-Thun-Ngan-Tay-Nam.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 4.5, 0, 'Xanh', NULL),
(18, 7, 'Áo thun ngắn tay nam', 199000.00, 169000.00, 'ao-Thun-Ngan-Tay-Nam.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 4.5, 0, 'Xanh', NULL),
(19, 7, 'Áo sơ mi Linen nam tay ngắn xanh', 299000.00, 259000.00, 'ao-Thun-Ngan-Tay-Nam.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 4.5, 0, 'Đen', NULL),
(20, 7, 'Áo sơ mi Linen nam tay ngắn xanh', 299000.00, 259000.00, 'ao-Thun-Ngan-Tay-Nam.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 4.5, 0, 'Đen', NULL),
(21, 7, 'Áo Sơ Mi Cuban Nam Họa Tiết Marvel Comic', 150000.00, 120000.00, 'ao-So-Mi-Cuban-Nam-Hoa-Tiet-Marvel-Comic.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Trắng đen', NULL),
(22, 7, 'Áo Sơ Mi Cuban Nam Họa Tiết Marvel Comic', 170000.00, 130000.00, 'ao-So-Mi-Cuban-Nam-Hoa-Tiet-Marvel-Comic.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Trắng đen', NULL),
(23, 7, 'Áo Sơ Mi Cuban Nam Tay', 210000.00, 180000.00, 'ao-So-Mi-Cuban-Nam-Tay.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Trắng', NULL),
(24, 7, 'Áo Sơ Mi Cuban Nam Tay', 225000.00, 190000.00, 'ao-So-Mi-Cuban-Nam-Tay.webp', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Trắng', NULL),
(25, 7, 'Áo sơ mi dài tay nam kẻ caro cotton màu xanh da trời', 195000.00, 165000.00, 'ao-so-mi-dai-tay-nam-ke-caro-cotton-mau-xanh-da-troi.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Xanh da trời', NULL),
(26, 7, 'Áo sơ mi dài tay nam kẻ caro cotton màu xanh da trời', 210000.00, 170000.00, 'ao-so-mi-dai-tay-nam-ke-caro-cotton-mau-xanh-da-troi.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh da trời', NULL),
(27, 7, 'Áo sơ mi dài tay nam kẻ caro màu xanh dương', 220000.00, 185000.00, 'ao-so-mi-dai-tay-nam-ke-caro-mau-xanh-duong.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Xanh dương', NULL),
(28, 7, 'Áo sơ mi dài tay nam kẻ caro màu xanh dương', 230000.00, 195000.00, 'ao-so-mi-dai-tay-nam-ke-caro-mau-xanh-duong.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh dương', NULL),
(29, 7, 'Áo sơ mi dài tay nam màu đen trơn', 300000.00, 265000.00, 'ao-so-mi-dai-tay-nam-mau-den-tron.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Đen', NULL),
(30, 7, 'Áo sơ mi dài tay nam màu đen trơn', 320000.00, 280000.00, 'ao-so-mi-dai-tay-nam-mau-den-tron.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Đen', NULL),
(31, 7, 'Áo sơ mi dài tay nam màu xanh', 250000.00, 220000.00, 'ao-so-mi-dai-tay-nam-mau-xanh.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Xanh', NULL),
(32, 7, 'Áo sơ mi dài tay nam màu xanh', 270000.00, 230000.00, 'ao-so-mi-dai-tay-nam-mau-xanh.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh', NULL),
(33, 7, 'Áo sơ mi dài tay nam tím than', 290000.00, 250000.00, 'ao-so-mi-dai-tay-nam-tim-than.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Tím than', NULL),
(34, 7, 'Áo sơ mi dài tay nam tím than', 310000.00, 270000.00, 'ao-so-mi-dai-tay-nam-tim-than.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Tím than', NULL),
(35, 7, 'Áo Sơ Mi Linen Nam Tay Ngắn Xanh', 190000.00, 150000.00, 'ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'L', NULL, NULL, 5, 0, 'Xanh rêu', NULL),
(36, 7, 'Áo Sơ Mi Linen Nam Tay Ngắn Xanh', 210000.00, 170000.00, 'ao-So-Mi-Linen-Nam-Tay-Ngan-Xanh.jpg', NULL, NULL, 'Sản phẩm thời trang nam cao cấp.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh rêu', NULL),
(37, 7, 'Quần short thể thao nam phối viền polyester', 200000.00, 170000.00, 'Quan-short-the-thao-nam-phoi-vien-polyester.webp', NULL, NULL, 'Sản phẩm quần short nam thoải mái, năng động.', 10, 'L', NULL, NULL, 5, 0, 'Xám', NULL),
(38, 7, 'Quần short thể thao nam phối viền polyester', 210000.00, 180000.00, 'Quan-short-the-thao-nam-phoi-vien-polyester.webp', NULL, NULL, 'Sản phẩm quần short nam thoải mái, năng động.', 10, 'XL', NULL, NULL, 5, 0, 'Xám', NULL),
(39, 7, 'Quần short denim nam form straight', 250000.00, 220000.00, 'Quan-short-denim-nam-form-straight.webp', NULL, NULL, 'Chất liệu denim bền đẹp, kiểu dáng trẻ trung.', 10, 'L', NULL, NULL, 5, 0, 'Xanh dương', NULL),
(40, 7, 'Quần short denim nam form straight', 270000.00, 230000.00, 'Quan-short-denim-nam-form-straight.webp', NULL, NULL, 'Chất liệu denim bền đẹp, kiểu dáng trẻ trung.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh dương', NULL),
(41, 7, 'Quần short nam nỉ gân French Terry form relax', 180000.00, 150000.00, 'Quan-short-nam-ni-gan-french-terry-form-relax.webp', NULL, NULL, 'Chất liệu nỉ cao cấp, thoáng mát, phù hợp tập luyện.', 10, 'L', NULL, NULL, 5, 0, 'Đen', NULL),
(42, 7, 'Quần short nam nỉ gân French Terry form relax', 190000.00, 160000.00, 'Quan-short-nam-ni-gan-french-terry-form-relax.webp', NULL, NULL, 'Chất liệu nỉ cao cấp, thoáng mát, phù hợp tập luyện.', 10, 'XL', NULL, NULL, 5, 0, 'Đen', NULL),
(43, 7, 'Quần short nam nylon form relax', 160000.00, 135000.00, 'Quan-short-nam-nylon-form-relax.webp', NULL, NULL, 'Kiểu dáng cơ bản, dễ phối đồ, chất liệu nhẹ.', 10, 'L', NULL, NULL, 5, 0, 'Kem', NULL),
(44, 7, 'Quần short nam nylon form relax', 180000.00, 150000.00, 'Quan-short-nam-nylon-form-relax.webp', NULL, NULL, 'Kiểu dáng cơ bản, dễ phối đồ, chất liệu nhẹ.', 10, 'XL', NULL, NULL, 5, 0, 'Kem', NULL),
(45, 7, 'Quần short nam trơn cotton form straight', 230000.00, 195000.00, 'Quan-short-nam-tron-cotton-form-straight.webp', NULL, NULL, 'Chất liệu cotton thấm hút, thiết kế đơn giản.', 10, 'L', NULL, NULL, 5, 0, 'Trắng', NULL),
(46, 7, 'Quần short nam trơn cotton form straight', 250000.00, 210000.00, 'Quan-short-nam-tron-cotton-form-straight.webp', NULL, NULL, 'Chất liệu cotton thấm hút, thiết kế đơn giản.', 10, 'XL', NULL, NULL, 5, 0, 'Trắng', NULL),
(47, 7, 'Quần short nỉ nam nhấn trang trí form relax', 200000.00, 170000.00, 'Quan-short-ni-nam-nhan-trang-tri-form-relax.webp', NULL, NULL, 'Thiết kế thời trang với điểm nhấn độc đáo.', 10, 'L', NULL, NULL, 5, 0, 'Xanh dương', NULL),
(48, 7, 'Quần short nỉ nam nhấn trang trí form relax', 210000.00, 180000.00, 'Quan-short-ni-nam-nhan-trang-tri-form-relax.webp', NULL, NULL, 'Thiết kế thời trang với điểm nhấn độc đáo.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh dương', NULL),
(49, 7, 'Quần shorts thể thao nam dây kéo sau regular', 270000.00, 230000.00, 'Quan-shorts-the-thao-nam-day-keo-sau-regular.webp', NULL, NULL, 'Form regular thể thao, dễ di chuyển, năng động.', 10, 'L', NULL, NULL, 5, 0, 'Xanh dương', NULL),
(50, 7, 'Quần shorts thể thao nam dây kéo sau regular', 290000.00, 250000.00, 'Quan-shorts-the-thao-nam-day-keo-sau-regular.webp', NULL, NULL, 'Form regular thể thao, dễ di chuyển, năng động.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh dương', NULL),
(51, 7, 'Quần dài nam điều gân form carrot', 260000.00, 230000.00, 'Quan-dai-nam-dieu-gan-form-carrot.webp', NULL, NULL, 'Chất vải điều gân thoải mái, form carrot trẻ trung.', 10, 'L', NULL, NULL, 5, 0, 'Đen', NULL),
(52, 7, 'Quần dài nam điều gân form carrot', 270000.00, 240000.00, 'Quan-dai-nam-dieu-gan-form-carrot.webp', NULL, NULL, 'Chất vải điều gân thoải mái, form carrot trẻ trung.', 10, 'XL', NULL, NULL, 5, 0, 'Đen', NULL),
(53, 7, 'Quần tây dài nam gấp ống form slim crop', 300000.00, 270000.00, 'Quan-tay-dai-nam-gap-ong-form-slimcrop-2.webp', NULL, NULL, 'Thiết kế gấp ống hiện đại, form ôm nhẹ.', 10, 'L', NULL, NULL, 5, 0, 'Đen', NULL),
(54, 7, 'Quần tây dài nam gấp ống form slim crop', 320000.00, 280000.00, 'Quan-tay-dai-nam-gap-ong-form-slimcrop-2.webp', NULL, NULL, 'Thiết kế gấp ống hiện đại, form ôm nhẹ.', 10, 'XL', NULL, NULL, 5, 0, 'Đen', NULL),
(55, 7, 'Quần tây nam dài lưng thun', 210000.00, 180000.00, 'Quan-tay-nam-dai-lung-thun.webp', NULL, NULL, 'Thiết kế thoải mái với lưng thun, dễ mặc.', 10, 'L', NULL, NULL, 5, 0, 'Xám', NULL),
(56, 7, 'Quần tây nam dài lưng thun', 220000.00, 190000.00, 'Quan-tay-nam-dai-lung-thun.webp', NULL, NULL, 'Thiết kế thoải mái với lưng thun, dễ mặc.', 10, 'XL', NULL, NULL, 5, 0, 'Xám', NULL),
(57, 7, 'Quần vải nam dài phối lót trơn form slim', 290000.00, 250000.00, 'Quan-vai-nam-dai-phoi-lot-tron-form-slim.webp', NULL, NULL, 'Lót trơn bên trong, sang trọng, lịch lãm.', 10, 'L', NULL, NULL, 5, 0, 'Trắng', NULL),
(58, 7, 'Quần vải nam dài phối lót trơn form slim', 310000.00, 270000.00, 'Quan-vai-nam-dai-phoi-lot-tron-form-slim.webp', NULL, NULL, 'Lót trơn bên trong, sang trọng, lịch lãm.', 10, 'XL', NULL, NULL, 5, 0, 'Trắng', NULL),
(59, 7, 'Quần denim nam ống rộng form loose', 270000.00, 235000.00, 'Quan-denim-nam-ong-rong-form-loose.webp', NULL, NULL, 'Phong cách trẻ trung, thoải mái vận động.', 10, 'L', NULL, NULL, 5, 0, 'Xanh jeans', NULL),
(60, 7, 'Quần denim nam ống rộng form loose', 290000.00, 250000.00, 'Quan-denim-nam-ong-rong-form-loose.webp', NULL, NULL, 'Phong cách trẻ trung, thoải mái vận động.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh jeans', NULL),
(61, 7, 'Quần denim nam ống đứng form straight', 260000.00, 225000.00, 'Quan-denim-nam-ong-dung-form-straight.webp', NULL, NULL, 'Thiết kế cổ điển, bền đẹp theo thời gian.', 10, 'L', NULL, NULL, 5, 0, 'Xanh đậm', NULL),
(62, 7, 'Quần denim nam ống đứng form straight', 280000.00, 240000.00, 'Quan-denim-nam-ong-dung-form-straight.webp', NULL, NULL, 'Thiết kế cổ điển, bền đẹp theo thời gian.', 10, 'XL', NULL, NULL, 5, 0, 'Xanh đậm', NULL),
(63, 7, 'Quần jeans nam dài recycled form cocoon', 300000.00, 260000.00, 'Quan-jeans-nam-dai-recycled-form-cocoon.webp', NULL, NULL, 'Sử dụng chất liệu recycled thân thiện môi trường.', 10, 'L', NULL, NULL, 5, 0, 'Đen', NULL),
(64, 7, 'Quần jeans nam dài recycled form cocoon', 320000.00, 270000.00, 'Quan-jeans-nam-dai-recycled-form-cocoon.webp', NULL, NULL, 'Sử dụng chất liệu recycled thân thiện môi trường.', 10, 'XL', NULL, NULL, 5, 0, 'Đen', NULL),
(65, 7, 'Quần tây nam ôm Twill Texture form slim crop', 280000.00, 250000.00, 'Quan-tay-nam-om-Twill-Texture-form-slim-crop.webp', NULL, NULL, 'Twill Texture tạo điểm nhấn cho trang phục.', 10, 'L', NULL, NULL, 5, 0, 'Vàng cam', NULL),
(66, 7, 'Quần tây nam ôm Twill Texture form slim crop', 295000.00, 260000.00, 'Quan-tay-nam-om-Twill-Texture-form-slim-crop.webp', NULL, NULL, 'Twill Texture tạo điểm nhấn cho trang phục.', 10, 'XL', NULL, NULL, 5, 0, 'Vàng cam', NULL),
(71, 13, 'Jamie shirt', 210000.00, 180000.00, 'Jamie-shirt.jpg', 'Jamie-shirt2.jpg', 'Jamie-shirt3.jpg', '...', 10, 'L', 'M', 'S', 5, 0, 'Trắng', NULL),
(73, 13, 'Rebel gile', 210000.00, 180000.00, 'Rebel-Gile.jpg', 'Rebel-Gile2.jpg', 'Rebel-Gile3.jpg', '...', 10, 'S', 'M', 'L', 5, 0, 'Đỏ', NULL),
(74, 13, 'Rebel gile', 210000.00, 180000.00, 'Rebel-Gile3.jpg', 'Rebel-Gile.jpg', 'Rebel-Gile2.jpg', '...', 10, 'S', 'M', 'L', 5, 0, 'Trắng', NULL),
(75, 13, 'Áo kiểu tay dài hoa nổi DUO STYLE - Esther Top', 210000.00, 180000.00, 'Esther-Top.jpg', 'Esther-Top2.jpg', NULL, '...', 10, 'S', 'M', 'L', 5, 0, 'Trắng', NULL),
(77, 13, 'Áo kiểu tay phồng cột nơ HYGGE - Naomi Top', 210000.00, 180000.00, 'Naomi-Top.jpg', 'Naomi-Top2.jpg', NULL, '...', 10, 'S', 'M', 'L', 5, 0, 'Trắng', NULL),
(79, 13, 'Ayako Top', 200000.00, 150000.00, 'Ayako-Top.jpg', 'Ayako-Top2.jpg', NULL, '...', 10, 'S', 'M', 'L', 5, 0, 'Trắng', NULL),
(81, 13, 'Betty Off-ShoulderTop', 220000.00, 200000.00, 'Betty-Off-ShoulderTop.jpg', 'Betty-Off-ShoulderTop2.jpg', 'Betty-Off-ShoulderTop3.jpg', '...', 10, 'S', 'M', 'L', 5, 0, 'Trắng', NULL),
(83, 13, 'Icy Top', 220000.00, 200000.00, 'icy-top.jpg', 'icy-top3.jpg', 'icy-top4.jpg', 'Áo Icy mang đến sự pha trộn độc đáo giữa nét nữ tính điệu đà và phong cách cá tính. Áo có thiết kế croptop dáng xòe với nhiều tầng bèo nhún bồng bềnh, tạo sự bay bổng và thu hút. Điểm nhấn là phần tay áo phồng xếp nếp ấn tượng và chi tiết dây rút trang trí dọc thân áo, mang đến vẻ ngoài phóng khoáng và độc đáo. Chất liệu vải mỏng nhẹ, màu trắng tinh khôi, rất phù hợp cho những ngày hè nóng bức. Áo Icy là lựa chọn lý tưởng để tạo điểm nhấn cho những bộ trang phục cá tính, có thể kết hợp với quần cạp cao hoặc chân váy để khoe vòng eo thon gọn.', 10, 'S', 'M', 'L', 5, 0, 'Trắng', NULL),
(84, 13, 'Icy Top', 220000.00, 200000.00, 'icy-top4.jpg', 'icy-top5.jpg', 'icy-top2.jpg', 'Áo Icy mang đến sự pha trộn độc đáo giữa nét nữ tính điệu đà và phong cách cá tính. Áo có thiết kế croptop dáng xòe với nhiều tầng bèo nhún bồng bềnh, tạo sự bay bổng và thu hút. Điểm nhấn là phần tay áo phồng xếp nếp ấn tượng và chi tiết dây rút trang trí dọc thân áo, mang đến vẻ ngoài phóng khoáng và độc đáo. Chất liệu vải mỏng nhẹ, màu trắng tinh khôi, rất phù hợp cho những ngày hè nóng bức. Áo Icy là lựa chọn lý tưởng để tạo điểm nhấn cho những bộ trang phục cá tính, có thể kết hợp với quần cạp cao hoặc chân váy để khoe vòng eo thon gọn.', 10, 'S', 'M', 'L', 5, 0, 'Vàng', NULL),
(87, 13, 'Kling Top', 200000.00, 180000.00, 'Kling-Top.jpg', 'Kling-Top2.jpg', NULL, '...', 10, 'S', 'M', 'L', 5, 0, 'Caro nâu', NULL),
(89, 13, 'Marisa Top', 200000.00, 180000.00, 'Marisa-Top.jpg', 'Marisa-Top2.jpg', NULL, '...', 10, 'S', 'M', 'L', 5, 0, 'Trắng', NULL),
(91, 13, 'Riso Tube Top', 200000.00, 180000.00, 'Riso-Tube-Top.jpg', 'Riso-Tube-Top2.jpg', 'Riso-Tube-Top3.jpg', '...', 10, 'S', 'M', 'L', 5, 0, 'Caro xanh', NULL),
(93, 13, 'Romie Cami Top', 200000.00, 180000.00, 'Romie-Cami-Top.jpg', 'Romie-Cami-Top2.jpg', NULL, '...', 10, 'S', 'M', NULL, 5, 0, 'Be', NULL),
(95, 13, 'Timo Jacket', 250000.00, 220000.00, 'Timo-Jacket.jpg', 'Timo-Jacket2.jpg', 'Timo-Jacket3.jpg', 'Áo khoác Timo mang đến sự năng động và trẻ trung với thiết kế croptop sành điệu, rất phù hợp cho những ngày se lạnh hoặc để tạo điểm nhấn cho trang phục. Khoác hoodie này có gam màu kem sữa nhẹ nhàng, dễ phối đồ, cùng với khóa kéo phía trước tiện lợi. Túi áo được thiết kế ở hai bên, viền may nổi bật, thêm phần cá tính. Chất liệu nỉ bông mềm mại, ấm áp, có mũ trùm đầu và dây rút điều chỉnh, mang lại sự thoải mái tối đa cho người mặc. Áo khoác Timo là lựa chọn lý tưởng cho các hoạt động ngoài trời, dạo phố hoặc đơn giản là một item thời trang hàng ngày.', 10, 'S', 'M', NULL, 5, 0, 'Be', NULL),
(97, 14, 'Chiara Dress', 299000.00, 279000.00, 'Chiara-Dress.jpg', 'Chiara-Dress2.jpg', 'Chiara-Dress3.jpg', '...', 10, 'M', 'L', 'S', 5, 0, 'Trắng', NULL),
(99, 14, 'Peachy Dress', 299000.00, 280000.00, 'Peachy-Dress.jpg', 'Peachy-Dress2.jpg', NULL, '...', 10, 'S', 'M', 'L', 5, 0, 'Hồng', NULL),
(101, 14, 'Pandora Dress', 299000.00, 280000.00, 'Pandora-Dress.jpg', 'Pandora-Dress2.jpg', NULL, '...', 10, 'S', 'M', NULL, 5, 0, 'Xanh đậm', NULL),
(103, 14, 'Samantha Dress', 299000.00, 269000.00, 'Samantha-Dress.jpg', 'Samantha-Dress2.jpg', 'Samantha-Dress3.jpg', '...', 10, 'L', 'M', 'S', 5, 0, 'Be', NULL),
(105, 14, 'Flurry Dress', 250000.00, 220000.00, 'Flurry-Dress.jpg', 'Flurry-Dress2.jpg', 'Flurry-Dress3.jpg', '...', 10, 'S', 'M', NULL, 5, 0, 'Caro', NULL),
(107, 14, 'Liltee Dress', 299000.00, 279000.00, 'Liltee-Dress.jpg', 'Liltee-Dress2.jpg', 'Liltee-Dress3.jpg', 'Chiếc váy Liltee mang phong cách hiện đại và cá tính. Thiết kế không tay, cổ tròn và dáng suông nhẹ ở thân trên tạo vẻ ngoài thanh lịch. Điểm nhấn độc đáo của chiếc váy là phần chân váy xếp ly xòe, được nhấn nhá bằng hai chi tiết đai khóa màu đen tương phản ở hai bên hông, tạo nên sự phá cách và ấn tượng. Chất liệu vải đứng dáng, giữ phom tốt, giúp tôn lên vẻ ngoài năng động nhưng vẫn không kém phần thời trang. Chiếc váy Liltee là lựa chọn lý tưởng cho những buổi đi chơi, dạo phố hay các sự kiện mang tính chất casual, dễ dàng kết hợp với nhiều phụ kiện để tạo nên phong cách riêng.', 10, 'S', 'M', '', 5, 0, 'Be', NULL),
(108, 14, 'Liltee Dress', 299000.00, 279000.00, 'Liltee-Dress2.jpg', 'Liltee-Dress.jpg', 'Liltee-Dress3.jpg', 'Chiếc váy Liltee mang phong cách hiện đại và cá tính. Thiết kế không tay, cổ tròn và dáng suông nhẹ ở thân trên tạo vẻ ngoài thanh lịch. Điểm nhấn độc đáo của chiếc váy là phần chân váy xếp ly xòe, được nhấn nhá bằng hai chi tiết đai khóa màu đen tương phản ở hai bên hông, tạo nên sự phá cách và ấn tượng. Chất liệu vải đứng dáng, giữ phom tốt, giúp tôn lên vẻ ngoài năng động nhưng vẫn không kém phần thời trang. Chiếc váy Liltee là lựa chọn lý tưởng cho những buổi đi chơi, dạo phố hay các sự kiện mang tính chất casual, dễ dàng kết hợp với nhiều phụ kiện để tạo nên phong cách riêng.', 10, 'S', 'M', NULL, 5, 0, 'Xanh đen', NULL),
(109, 14, 'LINDA DRESS', 299000.00, 280000.00, 'admin/assets/images/LINDA-DRESS.jpg', 'LINDA-DRESS2.jpg', 'LINDA-DRESS3.jpg', '...', 10, 'L', 'M', 'S', 5, 0, 'Xanh dương', NULL),
(111, 14, 'Oscar Midi Dress', 299000.00, 280000.00, 'Oscar-Midi-Dress.jpg', 'Oscar-Midi-Dress2.jpg', 'Oscar-Midi-Dress3.jpg', 'Chiếc váy Oscar có thiết kế đơn giản nhưng tinh tế với gam màu trắng thanh lịch. Dáng váy suông nhẹ, với phần trên ôm vừa vặn, không tay và cổ thuyền tạo cảm giác thoải mái nhưng vẫn giữ được nét thanh thoát. Điểm nhấn của chiếc váy là hàng cúc dọc thân trước, cùng với chi tiết xếp ly nhẹ nhàng ở phần chân váy, tạo độ bồng bềnh và nữ tính. Chất liệu vải nhẹ nhàng, thoáng mát, rất phù hợp cho những ngày hè năng động. Chiếc váy này là lựa chọn lý tưởng cho các buổi dạo phố, đi chơi hay những sự kiện không quá trang trọng, dễ dàng phối cùng giày thể thao hoặc sandal để tạo nên phong cách trẻ trung và tươi mới.', 10, 'S', 'M', 'L', 5, 0, 'Trắng', NULL),
(113, 14, 'Polo Dress', 299000.00, 269000.00, 'polo-dress.jpg', 'polo-dress2.jpg', NULL, 'Chiếc váy Polo mang phong cách preppy hiện đại với gam màu tím lavender nhẹ nhàng, nữ tính. Thiết kế dáng polo cổ bẻ màu trắng tương phản cùng tay áo bo viền trắng tạo nên vẻ ngoài thanh lịch và năng động. Phần thân trên có dáng suông thoải mái, trong khi chân váy xếp ly xòe điệu đà, mang lại sự trẻ trung và duyên dáng. Với phom dáng vừa vặn và chất liệu thoáng mát, chiếc váy này là lựa chọn hoàn hảo cho những buổi dạo chơi, đi học hoặc các hoạt động ngoài trời, dễ dàng phối cùng giày sneakers để hoàn thiện phong cách thể thao nhưng vẫn giữ được nét dịu dàng.', 10, 'L', 'M', 'S', 5, 0, 'Tím nhạt', NULL),
(115, 14, 'Prim Dress', 250000.00, 220000.00, 'prim-dress.jpg', 'prim-dress2.jpg', 'prim-dress3.jpg', 'Chiếc váy Prim mang đến sự kết hợp giữa nét cổ điển và hiện đại với họa tiết kẻ sọc caro tông xanh lá và xanh navy nổi bật. Thiết kế dáng slip dress với hai dây mảnh mai và đường cắt cúp vuông vắn ở phần ngực tạo vẻ ngoài thanh thoát. Điểm nhấn đặc biệt của chiếc váy là phần chân váy bất đối xứng, với một bên dài hơn và được trang trí bằng viền ren đen tinh xảo, tăng thêm vẻ nữ tính và độc đáo. Đây là lựa chọn lý tưởng cho những cô nàng yêu thích phong cách năng động nhưng vẫn giữ được nét duyên dáng, có thể dễ dàng phối cùng áo phông bên trong hoặc khoác ngoài tùy theo phong cách.', 10, 'L', 'M', 'S', 5, 0, 'Caro', NULL),
(117, 14, 'Rosie Dress', 299000.00, 269000.00, 'rosie-dress.jpg', 'rosie-dress2.jpg', 'rosie-dress3.jpg', 'Với kiểu dáng bồng bềnh và màu hồng pastel ngọt ngào, chiếc váy Rosie mang đến vẻ ngoài thanh lịch và nữ tính. Thiết kế cổ tròn, tay phồng xếp nếp điệu đà cùng chi tiết nhún bèo và nơ buộc tinh tế ở phần thân trước tạo điểm nhấn lãng mạn. Chất liệu vải mềm mại, có họa tiết sọc chìm nhẹ nhàng, tạo độ rủ và thoải mái khi mặc. Đây là lựa chọn hoàn hảo cho những buổi dạo phố, tiệc trà hay các sự kiện nhẹ nhàng, mang lại vẻ ngoài dịu dàng và đầy cuốn hút.', 10, 'S', 'M', 'L', 5, 0, 'Hồng', NULL),
(119, 14, 'Venis Dress', 250000.00, 220000.00, 'venis-dress.jpg', 'venis-dress2.jpg', 'venis-dress3.jpg', 'Chiếc đầm Venis mang đến vẻ trẻ trung và đầy sức sống với họa tiết kẻ caro xanh dịu mát cùng phom dáng A-line ngọt ngào. Phần thân trên ôm vừa vặn, kết hợp chân váy xếp ly điệu đà tạo nên sự bồng bềnh nữ tính rất đỗi thanh thoát. Đây sẽ là lựa chọn tuyệt vời cho những buổi dạo chơi cuối tuần hay những buổi gặp gỡ bạn bè, giúp nàng thêm phần rạng rỡ và cuốn hút.', 10, 'L', 'M', 'S', 5, 0, 'Caro xanh nhạt', NULL),
(121, 15, 'Bonew Parachute Short', 199000.00, 179000.00, 'Bonew-Parachute-Short.jpg', 'Bonew-Parachute-Short2.jpg', NULL, 'Khám phá vẻ đẹp trẻ trung và năng động cùng quần short Bonew, sắc kem dịu nhẹ mang đến cảm giác bay bổng, thoải mái. Thiết kế phom dáng rộng rãi, lấy cảm hứng từ parachute, kết hợp chi tiết nút cài độc đáo và dây rút điều chỉnh, tạo nên sự phá cách đầy cuốn hút. Một lựa chọn lý tưởng cho những ngày hè rực rỡ, giúp nàng tự tin thể hiện phong thái tự do và đầy cá tính.', 10, 'S', 'M', NULL, 5, 0, 'Be', NULL),
(123, 15, 'Fomos Jean Short dark blue', 299000.00, 280000.00, 'Fomos-Jean-Short-dark-blue.jpg', 'Fomos-Jean-Short-dark-blue2.jpg', 'Fomos-Jean-Short-light-blue2.jpg', 'Khám phá vẻ đẹp cá tính cùng Fomos Jean Short, sắc xanh denim đậm thời thượng mang đến phong thái mạnh mẽ và hiện đại. Thiết kế lửng ống rộng với độ dài ngang bắp chân tạo nên sự thoải mái tuyệt đối và vẻ ngoài phá cách. Đây là lựa chọn hoàn hảo cho những ngày hè năng động, dễ dàng kết hợp cùng nhiều kiểu áo để tạo nên dấu ấn riêng biệt đầy cuốn hút.', 10, 'L', 'M', 'S', 5, 0, 'dark jeans', ''),
(124, 15, 'Fomos Jean Short dark blue', 299000.00, 280000.00, 'Fomos-Jean-Short-light-blue2.jpg', 'Fomos-Jean-Short-dark-blue2.jpg', 'Fomos-Jean-Short-dark-blue.jpg', 'Khám phá vẻ đẹp cá tính cùng Fomos Jean Short, sắc xanh denim đậm thời thượng mang đến phong thái mạnh mẽ và hiện đại. Thiết kế lửng ống rộng với độ dài ngang bắp chân tạo nên sự thoải mái tuyệt đối và vẻ ngoài phá cách. Đây là lựa chọn hoàn hảo cho những ngày hè năng động, dễ dàng kết hợp cùng nhiều kiểu áo để tạo nên dấu ấn riêng biệt đầy cuốn hút.', 10, 'L', 'M', 'S', 5, 0, 'light jeans', ''),
(127, 15, 'Hebe Jeans', 299000.00, 280000.00, 'admin/assets/images/hebe-jeans.jpg', NULL, NULL, 'Nắm bắt tinh thần phóng khoáng với chiếc quần jean Hebe, sắc kem nhẹ nhàng thổi làn gió mới vào phong cách thường nhật. Thiết kế ống rộng thời thượng kết hợp cùng chi tiết xếp ly tinh tế, tạo nên vẻ ngoài bay bổng, thanh lịch mà vẫn giữ trọn sự thoải mái. Đây là item lý tưởng cho những ngày dài năng động, giúp nàng tự tin thể hiện cá tính riêng đầy cuốn hút.', 10, 'L', NULL, NULL, 5, 0, 'Trắng', NULL),
(128, 15, 'Hebe Jeans', 200000.00, 280000.00, 'admin/assets/images/hebe-jeans2.jpg', NULL, NULL, 'Nắm bắt tinh thần phóng khoáng với chiếc quần jean Hebe, sắc kem nhẹ nhàng thổi làn gió mới vào phong cách thường nhật. Thiết kế ống rộng thời thượng kết hợp cùng chi tiết xếp ly tinh tế, tạo nên vẻ ngoài bay bổng, thanh lịch mà vẫn giữ trọn sự thoải mái. Đây là item lý tưởng cho những ngày dài năng động, giúp nàng tự tin thể hiện cá tính riêng đầy cuốn hút.', 10, 'M', NULL, NULL, 5, 0, 'Trắng', NULL),
(129, 15, 'Pamin Pants', 250000.00, 220000.00, 'admin/assets/images/Pamin-Pants.jpg', 'Pamin-Pants2.jpg', 'Pamin-Pants3.jpg', 'Khám phá sự tự do trong phong cách với chiếc quần Pamin Pants, sắc xanh baby dịu mát mang đến làn gió tươi mới. Thiết kế ống rộng thoải mái kết hợp cùng túi hộp năng động, tạo nên vẻ ngoài cá tính nhưng vẫn giữ được nét thanh thoát, nhẹ nhàng. Item hoàn hảo cho những ngày cần sự linh hoạt, dễ dàng phối hợp cho mọi hoạt động, từ dạo phố đến những chuyến đi khám phá.', 10, 'L', 'M', 'S', 5, 0, 'Xanh', NULL),
(131, 15, 'Pull Pants', 299000.00, 279000.00, 'admin/assets/images/pull-pants.jpg', 'pull-pants2.jpg', 'pull-pants3.jpg', 'Đón chào sự trở lại của phong cách retro cùng chiếc quần jean Pull-Pants, sắc xanh denim cổ điển hòa quyện cùng nét hiện đại. Thiết kế ống rộng thời thượng mang đến sự thoải mái tối ưu và vẻ ngoài phóng khoáng, trong khi đường may tinh tế tạo điểm nhấn cho tổng thể trang phục. Đây là mảnh ghép lý tưởng cho những bộ cánh dạo phố đầy cá tính hay những buổi hẹn hò, giúp nàng tự do thể hiện chất riêng đầy thu hút.', 10, 'L', NULL, NULL, 5, 0, 'Xanh', NULL),
(133, 15, 'Strip Long Pants', 299000.00, 280000.00, 'admin/assets/images/Strip-Long-Pants.jpg', 'Strip-Long-Pants2.jpg', 'Strip-Long-Pants3.jpg', 'Khơi gợi phong cách tối giản đầy cuốn hút cùng quần Strip Long Pants, nổi bật với họa tiết sọc mảnh tinh tế trên nền vải mềm mại. Thiết kế ống suông rộng rãi mang lại sự thoải mái vượt trội và nét thanh lịch tự nhiên, dễ dàng kết hợp cho mọi dáng người. Một lựa chọn lý tưởng cho những ngày dài năng động, giúp nàng tự tin thể hiện vẻ đẹp nhẹ nhàng mà vẫn rất thời thượng.', 10, 'L', 'M', NULL, 5, 0, 'Caro', NULL),
(135, 16, 'Bubble Skirt', 199000.00, 180000.00, 'admin/assets/images/bubble-skirt.jpg', 'bubble-skirt2.jpg', 'bubble-skirt3.jpg', 'Chân váy bubble thiết kế phồng nhẹ với hoạ tiết kẻ caro xanh pastel mang đến vẻ ngoài ngọt ngào và cuốn hút. Chất vải mềm, nhẹ, tạo độ bồng tự nhiên giúp tôn dáng và hack chiều cao hiệu quả. Dễ phối cùng áo trễ vai, áo croptop hoặc cardigan mỏng cho phong cách chuẩn Hàn Quốc.\r\n\r\n', 10, 'S', 'M', NULL, 5, 0, 'Xanh trắng', NULL),
(137, 16, 'Doris Midi Skirt', 299000.00, 269000.00, 'admin/assets/images/Doris-Midi-Skirt2.jpg', 'Doris-Midi-Skirt.jpg', 'Doris-Midi-Skirt3.jpg', 'Nổi bật với sắc trắng tinh khôi, chân váy xếp ly Doris Midi Skirt mang đến vẻ đẹp thanh lịch và bay bổng. Thiết kế xếp ly đều đặn tạo hiệu ứng xòe nhẹ nhàng, phù hợp cho những cô nàng yêu thích phong cách trẻ trung, nữ tính và đầy năng động.', 10, 'M', 'L', NULL, 5, 0, 'Trắng', NULL),
(139, 16, 'Ficus Skirt', 250000.00, 220000.00, 'admin/assets/images/ficus-skirt.jpg', 'ficus-skirt2.jpg', 'ficus-skirt3.jpg', 'Ficus Skirt nổi bật với thiết kế chân váy xếp ly giả quần, mang đến sự tự tin và năng động cho nàng. Chất vải dày dặn, đứng form, phối cùng chi tiết kim loại nhỏ tạo điểm nhấn tinh tế. Dễ dàng kết hợp với áo sơ mi, áo croptop hay cardigan cho phong cách thanh lịch nhưng vẫn trẻ trung, phù hợp đi học, đi chơi hay dạo phố.', 10, 'S', 'M', NULL, 5, 0, 'Xám', NULL),
(141, 16, 'Hansy Mini Skirt', 199000.00, 169000.00, 'admin/assets/images/Hansy-Mini-Skirt.jpg', 'Hansy-Mini-Skirt2.jpg', 'Hansy-Mini-Skirt3.jpg', 'Hansy Mini Skirt thiết kế dáng ngắn ôm nhẹ, tôn dáng hiệu quả và giúp đôi chân thêm dài. Gam màu kem trung tính dễ phối đồ, phù hợp cả khi đi học, đi chơi hay dạo phố. Chất vải dày vừa phải, mềm mịn, giữ form tốt. Item lý tưởng cho nàng yêu thích phong cách basic nhưng vẫn muốn thật nổi bật.', 10, 'S', 'M', NULL, 5, 0, 'Be', NULL),
(143, 16, 'Hiba Skirt', 150000.00, 120000.00, 'admin/assets/images/Hiba-Skirt.jpg', 'Hiba-Skirt2.jpg', 'Hiba-Skirt3.jpg', 'Chiếc chân váy Hiba với thiết kế họa tiết sọc ngang đỏ trắng năng động, cùng chi tiết dây rút cá tính ở cạp, mang lại vẻ ngoài trẻ trung và thoải mái, lý tưởng cho mọi hoạt động thường ngày.', 10, 'S', 'M', 'L', 5, 0, 'Caro đỏ', NULL),
(145, 16, 'Nixie Pleated Skirt', 250000.00, 220000.00, 'admin/assets/images/Nixie-Pleated-Skirt.jpg', 'Nixie-Pleated-Skirt2.jpg', 'Nixie-Pleated-Skirt3.jpg', 'Nixie Pleated Skirt sở hữu thiết kế xòe nhẹ cùng nếp gấp mềm mại, tạo cảm giác bồng bềnh nữ tính. Tông màu kem ngọt ngào phối dây rút đen nổi bật, mang lại vẻ năng động mà vẫn thanh lịch. Kiểu dáng giả quần giúp nàng thoải mái vận động, phù hợp cho các buổi dạo phố, chụp ảnh hay đi chơi cuối tuần.', 10, 'S', 'M', 'L', 5, 0, 'Be', NULL),
(147, 16, 'Rolly Bubble Skirt', 199000.00, 169000.00, 'Rolly-Bubble-Skirt.jpg', 'Rolly-Bubble-Skirt2.jpg', 'Rolly-Bubble-Skirt3.jpg', 'Với thiết kế dáng phồng độc đáo cùng gam màu đen basic, Rolly Bubble Skirt là item must-have cho nàng yêu phong cách thời trang hiện đại. Phần cạp co giãn kèm dây rút giúp ôm vừa eo, tạo form chuẩn mà vẫn thoải mái. Dễ mix cùng áo thun, croptop hay hoodie để tạo nên outfit cá tính và năng động mỗi ngày.', 10, 'S', 'M', 'L', 5, 0, 'Đen', NULL),
(149, 16, 'Tacha Bubble Skirt', 150000.00, 120000.00, 'Tacha-Bubble-Skirt.jpg', 'Tacha-Bubble-Skirt2.jpg', 'Tacha-Bubble-Skirt3.jpg', 'Mang phong cách trẻ trung với thiết kế dáng phồng độc đáo, chân váy Bubble Tacha kẻ caro giúp bạn nổi bật trong mọi khung hình. Form ngắn, tạo hiệu ứng chân dài, cực hợp với áo croptop hoặc sơ mi basic. Chất vải mềm mại, dễ phối đồ – lựa chọn lý tưởng cho các buổi đi chơi, chụp hình hay hẹn hò cuối tuần.\r\n\r\n', 10, 'S', 'M', NULL, 5, 0, 'Caro', NULL);

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `before_insert_order_rating` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    IF NEW.rating < 1 OR NEW.rating > 5 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Rating must be between 1 and 5';
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `cid_FK` (`cid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
