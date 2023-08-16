import React, { useEffect, useState } from "react";
import type { MenuProps } from "antd";
import { Menu } from "antd";
import Image from "next/image";
import "./styles.scss";
import Link from "next/link";
import { Cookies } from "react-cookie";
import { BarsOutlined } from "@ant-design/icons";

type MenuItem = Required<MenuProps>["items"][number];

function getItem(
  label: React.ReactNode,
  key: React.Key,
  icon?: React.ReactNode,
  children?: MenuItem[],
  type?: "group"
): MenuItem {
  return {
    key,
    icon,
    children,
    label,
    type,
  } as MenuItem;
}

const items: MenuItem[] = [
  getItem(
    <Link href={"/"}>Início</Link>,
    "1",
    <Image src="/despesas.svg" alt="Logo" width={25} height={25} />
  ),
  getItem(
    <Link href={"/EnterTransaction"}>Entradas</Link>,
    "2",
    <Image src="/bolsa-de-dinheiro.svg" alt="Logo" width={25} height={25} />
  ),
  getItem(
    <Link href={"/Outputs"}>Saídas</Link>,
    "3",
    <Image src="/wallet.svg" alt="Logo" width={25} height={25} />
  ),
  getItem("Meus cartões", "4", <Image src="/cartoes-de-credito.svg" alt="Logo" width={25} height={25} />),
  getItem("Análises", "5", <Image src="/analise.svg" alt="Logo" width={25} height={25} />),
  getItem("Meu perfil", "6", <Image src="/perfil2.svg" alt="Logo" width={25} height={25} />),
  getItem("Sair", "7", <Image src="/exit1.svg" alt="Logo" width={25} height={25} />),
];

const CustomMenu: React.FC = () => {
  const cookie = new Cookies();
  const menu = cookie.get("menu");
  const [showMenu, setShowMenu] = useState(window.innerWidth > 768);

  const toggleMenu = () => {
    setShowMenu(!showMenu);
  };

  return (
    <div>
      <BarsOutlined onClick={toggleMenu} className="burger-icon" />
      {showMenu && (
        <Menu
          style={{ width: 250, height: "90vh" }}
          defaultSelectedKeys={menu ? [menu.toString()] : ["1"]}
          mode="inline"
          items={items}
          onClick={({ key }) => cookie.set("menu", key)}
        />
      )}
    </div>
  );
};

export default CustomMenu;