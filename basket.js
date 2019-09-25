//basket
	let updateBasketEvent = new Event("updateBasketEvent", {bubbles: true});
	function Basket()
	{
		const basketWrapper = document.querySelector('#basksetWrapper');
		const basketFlag = document.querySelector('#basketFlag');// если есть эта нода значит в корзине есть товар
		const sideCart = document.querySelector(`.side-cart`);
		const cartToggler = document.querySelector(`.side-cart__toggler`);
		const buyBtns = document.querySelectorAll('.buy_link');
		const smallBasketWrapper = document.querySelector('#smallBasketWrapper');
		let smallBasketQuant = document.querySelector('#smallBasketQuant');
		let smallBasketPrice = document.querySelector('#smallBasketPrice');

		const UpdateBasket = () =>
		{
			fetch('/local/inc_files/basket.php?fetch=y')
			.then(response=>
			{
				if(response.status === 200)
					return response.text();
				else
					return false;
			})
			.then(data => 
			{
				if(data !== false)
				{
					basketWrapper.innerHTML = data;
					Basket();
				}
			});
		}
		const UpdateHandler = (id, quant) =>
		{
			fetch('/local/inc_files/action.php?action=update&id='+id+'&quantity='+quant);
			UpdateBasket();
		}
		const IncBtnHandler = (e)=>
		{
			let quant = e.target.previousElementSibling;
			quant.innerText = parseInt(quant.innerText) + 1;
			UpdateHandler(e.target.dataset.id, quant.innerText);
		}
		const DecBtnHandler = (e)=>
		{
			let quant = e.target.nextElementSibling;
			if(quant.innerText == '1')
				return false;
			quant.innerText = parseInt(quant.innerText) - 1;
			UpdateHandler(e.target.dataset.id, quant.innerText);
		}
		const DelBtnHandler = (e) =>
		{
			fetch('/local/inc_files/action.php?action=delete&id='+e.target.dataset.id).then(response=>
			{
				if(response.status === 200)
					document.querySelector('#id'+e.target.dataset.id).remove();
			});
			UpdateBasket();
		}
		const onCartTogglerClick = () => {
			sideCart.classList.toggle(`side-cart--open`);
		};
		const BuyBtnHandler = () => {
			setTimeout(UpdateBasket, 2300);
		}

		if(basketFlag)
		{
			$(".nano.side-cart__scrollable").nanoScroller();
			const incBtns = document.querySelectorAll('.js-increment');
			const decBtns = document.querySelectorAll('.js-decrement');
			const deleteBtns = document.querySelectorAll('.js-delete');
			
			let basketItemsQuant = document.querySelector('#basketItemsQuant').dataset.value;
			let basketAllSum = document.querySelector('#basketAllSum').dataset.value;

			incBtns.forEach(btn=>btn.addEventListener('click', IncBtnHandler));
			decBtns.forEach(btn=>btn.addEventListener('click', DecBtnHandler));
			deleteBtns.forEach(btn=>btn.addEventListener('click', DelBtnHandler));

			smallBasketQuant.innerText = basketItemsQuant;
			smallBasketPrice.innerText = basketAllSum;
			smallBasketPrice.dispatchEvent(updateBasketEvent);
			
		}
		else
			smallBasketWrapper.innerHTML = '<p class="empty_cart">Корзина пуста</p>';

		buyBtns.forEach(btn=>btn.addEventListener('click', BuyBtnHandler));
		cartToggler.addEventListener(`click`, onCartTogglerClick);

	}

	Basket();