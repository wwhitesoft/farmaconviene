/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


Aimeos.Address = {

	init() {
		const node = document.querySelector('#item-address-group');

		if(node) {
			Aimeos.apps['address'] = Aimeos.app({
				props: {
					data: {type: String, default: '[]'},
					domain: {type: String, default: ''},
					siteid: {type: String, default: ''},
				},
				data() {
					return {
						items: [],
						show: false
					}
				},
				mounted() {
					this.Aimeos = Aimeos;
					this.items = JSON.parse(this.data);

					for(const entry of this.items) {
						entry['_center'] = this.point(entry);
						entry['_zoom'] = entry[this.domain + '.address.latitude'] ? 8 : 2;
					}

					if(this.items[0]) {
						this.items[0]['_show'] = true;
					}

					const self = this;
					Aimeos.lazy(node, function() {
						self.show = true;
					});
				},
				mixins: [this.mixins]
			}, {...node.dataset || {}}).mount(node);
		}
	},


	mixins: {
		methods: {
			add(data = {}) {
				const entry = {};

				entry[this.domain + '.address.siteid'] = this.siteid;
				entry[this.domain + '.address.id'] = null;
				entry[this.domain + '.address.title'] = null;
				entry[this.domain + '.address.salutation'] = null;
				entry[this.domain + '.address.firstname'] = null;
				entry[this.domain + '.address.lastname'] = null;
				entry[this.domain + '.address.address1'] = null;
				entry[this.domain + '.address.address2'] = null;
				entry[this.domain + '.address.address3'] = null;
				entry[this.domain + '.address.postal'] = null;
				entry[this.domain + '.address.city'] = null;
				entry[this.domain + '.address.state'] = null;
				entry[this.domain + '.address.countryid'] = '';
				entry[this.domain + '.address.languageid'] = null;
				entry[this.domain + '.address.email'] = null;
				entry[this.domain + '.address.website'] = null;
				entry[this.domain + '.address.telephone'] = null;
				entry[this.domain + '.address.telefax'] = null;
				entry[this.domain + '.address.latitude'] = null;
				entry[this.domain + '.address.longitude'] = null;
				entry[this.domain + '.address.company'] = null;
				entry[this.domain + '.address.vatid'] = null;

				entry['_center'] = [0, 0];
				entry['_zoom'] = 2;
				entry['_show'] = true;

				this.items.push(Object.assign(entry, data));
			},


			can(action, idx) {
				return Aimeos.can(action, this.items[idx][this.domain + '.address.siteid'] || null, this.siteid)
			},


			duplicate(idx) {
				if(this.items[idx]) {
					this.items[this.items.length] = JSON.parse(JSON.stringify(this.items[idx]));
				}
			},


			remove(idx) {
				if(this.items[idx]) {
					this.items.splice(idx, 1);
				}
			},


			label(idx) {
				let label = '', addr = '';

				if(this.items[idx]) {
					label += (this.items[idx][this.domain + '.address.firstname'] ? this.items[idx][this.domain + '.address.firstname'] + ' ' : '');
					label += (this.items[idx][this.domain + '.address.lastname'] ? this.items[idx][this.domain + '.address.lastname'] : '');

					addr += (this.items[idx][this.domain + '.address.postal'] ? ' ' + this.items[idx][this.domain + '.address.postal'] : '');
					addr += (this.items[idx][this.domain + '.address.city'] ? ' ' + this.items[idx][this.domain + '.address.city'] : '');
				}

				if(addr && label) {
					return label + ' -' + addr;
				}

				return label + ' ' + addr;
			},


			toggle(what, idx) {
				if(this.items[idx]) {
					this.items[idx][what] = (!this.items[idx][what] ? true : false);
				}
			},


			point(entry) {
				return [entry[this.domain + '.address.latitude'] || 0, entry[this.domain + '.address.longitude'] || 0];
			},


			setPoint(idx, ev) {
				this.items[idx]['_center'] = ev.latlng;
				this.items[idx][this.domain + '.address.latitude'] = ev.latlng.lat || null;
				this.items[idx][this.domain + '.address.longitude'] = ev.latlng.lng || null;
			},


			zoom(idx) {
				return this.items[idx][this.domain + '.address.latitude'] && this.items[idx][this.domain + '.address.longitude'] ? 8 : 2;
			}
		}
	}
};


document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Address.init();
});
